<?php
/**
 * Choghadiya controller.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2022 Ennexa Technologies Private Limited
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Prokerala\WP\Astrology\Front\Report;

use DateTimeImmutable;
use Exception;
use Prokerala\Api\Astrology\Western\Service\Charts\SynastryChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SynastryChart as SynastryAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SynastryChart as SynastryPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Synastry Chart Form Controller.
 *
 * @since   1.0.0
 */
class SynastryChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * SynastryChartController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.2.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults(): array {
		return $this->getCommonAttributeDefaults() + [
			'date'       => '',
			'filter'     => 'chart',
			'coordinate' => '',
		];
	}

	/**
	 * Render Synastry Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );
		$filter           = explode( ',', $options['filter'] );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$aspect_filter                = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : 'major';
		$house_system                 = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : 'placidus';
		$chart_type                   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : 'zodiac-contact-chart';
		$orb                          = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : 'default';
		$rectification_chart          = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : 'flat-chart';
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/synastry-chart',
			[
				'options'                      => $options + $this->get_options(),
				'primary_birth_time'           => ( new DateTimeImmutable( $datetime, $this->get_timezone() ) )->modify( '-25 years' ),
				'secondary_birth_time'         => ( new DateTimeImmutable( $datetime, $this->get_timezone() ) )->modify( '-20 years' ),
				'synastry'                     => true,
				'aspect_filter'                => ( in_array( 'planet-positions', $filter, true ) || in_array( 'planet-aspects', $filter, true ) ) ? null : $aspect_filter,
				'house_system'                 => $house_system,
				'primary_birth_time_unknown'   => $primary_birth_time_unknown,
				'secondary_birth_time_unknown' => $secondary_birth_time_unknown,
				'rectification_chart'          => $rectification_chart,
				'chart_type'                   => $chart_type,
				'orb'                          => $orb,
				'selected_lang'                => $form_language,
				'report_language'              => $report_language,
				'translation_data'             => $translation_data,
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client = $this->get_api_client();

		$primary_tz             = $this->get_timezone( 'partner_a_' );
		$primary_birth_location = $this->get_location( $primary_tz, 'partner_a_' );

		$secondary_tz             = $this->get_timezone( 'partner_b_' );
		$secondary_birth_location = $this->get_location( $secondary_tz, 'partner_b_' );

		$filter = explode( ',', $options['filter'] );
		if ( in_array( 'all', $filter, true ) ) {
			$filter = [ 'chart', 'aspect-chart', 'planet-positions', 'planet-aspects' ];
		}
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondary_birth_time = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';

		$chart_type   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : '';
		$house_system = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : '';
		$orb          = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : '';

		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$rectification_chart          = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : '';

		$aspect_filter = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$primary_birth_time   = new DateTimeImmutable( $primary_birth_time, $primary_tz );
		$secondary_birth_time = new DateTimeImmutable( $secondary_birth_time, $secondary_tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		if ( in_array( 'chart', $filter, true ) ) {
			$method = new SynastryChart( $client );
			$chart  = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}

		if ( in_array( 'aspect-chart', $filter, true ) ) {
			$method       = new SynastryAspectChart( $client );
			$aspect_chart = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'planet-aspects', $filter, true ) ) {
			$method         = new SynastryPlanetPositions( $client );
			$planet_aspects = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart );
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/synastry-chart',
			[
				'chart'            => $chart ?? null,
				'aspect_chart'     => $aspect_chart ?? null,
				'planet_aspects'   => $planet_aspects ?? null,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}
}
