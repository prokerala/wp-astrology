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
use Prokerala\Api\Astrology\Western\Service\Charts\TransitChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\TransitChart as TransitAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\TransitChart as TransitPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Transit Chart Form Controller.
 *
 * @since   1.0.0
 */
class TransitChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * TransitChartController constructor
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
			'date'            => '',
			'display_options' => 'chart',
			'coordinate'      => '',
		];
	}

	/**
	 * Render Transit Chart form.
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
		$display_options  = explode( ',', $options['display_options'] );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$aspect_filter       = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : 'major';
		$house_system        = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : 'placidus';
		$orb                 = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : 'default';
		$rectification_chart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : 'flat-chart';
		$birth_time_unknown  = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return $this->render(
			'form/transit-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => ( new DateTimeImmutable( $datetime, $this->get_timezone() ) )->modify( '-10 years' ),
				'transit_datetime'    => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'transit'             => true,
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
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
		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$transit_tz       = $this->get_timezone( 'current_' );
		$transit_location = $this->get_location( $transit_tz, 'current_' );

		$display_options = explode( ',', $options['display_options'] );
		if ( in_array( 'all', $display_options, true ) ) {
			$display_options = [ 'chart', 'aspect-chart', 'planet-positions', 'planet-aspects' ];
		}
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime         = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		$transit_datetime = isset( $_POST['transit_datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';

		$house_system        = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : '';
		$orb                 = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : '';
		$birth_time_unknown  = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$rectification_chart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : '';
		$aspect_filter       = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$datetime         = new DateTimeImmutable( $datetime, $tz );
		$transit_datetime = new DateTimeImmutable( $transit_datetime, $tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$planet_positions = in_array( 'planet-positions', $display_options, true );
		$planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new TransitChart( $client );
			$chart  = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new TransitAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $planet_positions || $planet_aspects ) {
			$method = new TransitPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart );
		}
		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/transit-chart',
			[
				'chart'            => $chart ?? null,
				'aspect_chart'     => $aspect_chart ?? null,
				'result'           => $result ?? null,
				'planet_positions' => $planet_positions,
				'planet_aspects'   => $planet_aspects,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}
}
