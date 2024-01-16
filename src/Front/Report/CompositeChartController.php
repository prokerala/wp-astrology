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
use Prokerala\Api\Astrology\Western\Service\Charts\CompositeChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\CompositeChart as CompositeAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\CompositeChart as CompositePlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Composite Chart Form Controller.
 *
 * @since   1.0.0
 */
class CompositeChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * CompositeChartController constructor
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
				'filter' 	 => 'chart',
				'coordinate' => '',
			];
	}

	/**
	 * Render Composite Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );
		$filter = explode(',', $options['filter']);
		$aspectFilter = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : '';
		$houseSystem = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : '';

		return $this->render(
			'form/composite-chart',
			[
				'options'          	 => $options + $this->get_options(),
				'primaryBirthTime' 	 => (new DateTimeImmutable( $datetime, $this->get_timezone() ))->modify('-25 years'),
				'secondaryBirthTime' => (new DateTimeImmutable( $datetime, $this->get_timezone() ))->modify('-25 years'),
				'transitDateTime'  	 => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'composite'   	   	 => true,
				'aspectFilter'     	 => in_array('planet-positions', $filter) ? false : $aspectFilter,
				'houseSystem'     	 => $houseSystem,
				'selected_lang'    	 => $form_language,
				'report_language'  	 => $report_language,
				'translation_data' 	 => $translation_data,
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
	public function process( $options = [] ) {
		$tz       		 = $this->get_timezone();
		$client   		 = $this->get_api_client();
		$currentLocation = $this->get_location( $tz );

		$primaryTz       = $this->get_timezone('partner_a_');
		$primaryBirthLocation = $this->get_location( $primaryTz, 'partner_a_' );

		$secondaryTz       = $this->get_timezone('partner_b_');
		$secondaryLocation = $this->get_location( $secondaryTz, 'partner_b_' );

		$filter = explode(',', $options['filter']);

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$transitDateTime = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';
		$primaryBirthTime = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondaryBirthTime = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';

		$houseSystem = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : '';
		$orb = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : '';

		$primaryBirthTimeUnknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondaryBirthTimeUnknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$rectificationChart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : '';

		$aspectFilter = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$transitDateTime = new DateTimeImmutable( $transitDateTime, $tz );
		$primaryBirthTime = new DateTimeImmutable( $primaryBirthTime, $primaryTz );
		$secondaryBirthTime = new DateTimeImmutable( $secondaryBirthTime, $secondaryTz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		if (in_array('chart', $filter)) {
			$method = new CompositeChart($client);
			$chart = $method->process($primaryBirthLocation, $primaryBirthTime, $secondaryLocation, $secondaryBirthTime, $currentLocation, $transitDateTime, $houseSystem, $orb, $primaryBirthTimeUnknown, $secondaryBirthTimeUnknown, $rectificationChart, $aspectFilter);
		}
		if (in_array('aspect-chart', $filter)) {
			$method = new CompositeAspectChart( $client );
			$aspectChart = $method->process($primaryBirthLocation, $primaryBirthTime, $secondaryLocation, $secondaryBirthTime, $currentLocation, $transitDateTime, $houseSystem, $orb, $primaryBirthTimeUnknown, $secondaryBirthTimeUnknown, $rectificationChart, $aspectFilter);
		}
		if (in_array('planet-positions', $filter)) {
			$method = new CompositePlanetPositions( $client );
			$result = $method->process($primaryBirthLocation, $primaryBirthTime, $secondaryLocation, $secondaryBirthTime, $currentLocation, $transitDateTime, $houseSystem, $orb, $primaryBirthTimeUnknown, $secondaryBirthTimeUnknown, $rectificationChart);
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/composite-chart',
			[
				'chart'            => $chart ?? null,
				'aspectChart'      => $aspectChart ?? null,
				'result'		   => $result ?? null,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}
}
