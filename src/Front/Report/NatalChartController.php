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
use Prokerala\Api\Astrology\Western\Service\Charts\NatalChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\NatalChart as NatalAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\NatalChart as NatalPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Natal Chart Form Controller.
 *
 * @since   1.0.0
 */
class NatalChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * NatalChartController constructor
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
	 * Render Natal Chart form.
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
			'form/natal-chart',
			[
				'options'          => $options + $this->get_options(),
				'datetime'         => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'aspectFilter'     => in_array('planet-positions', $filter) ? false : $aspectFilter,
				'houseSystem'      => $houseSystem,
				'selected_lang'    => $form_language,
				'report_language'  => $report_language,
				'translation_data' => $translation_data,
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
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		$filter = explode(',', $options['filter']);
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		$houseSystem = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : '';
		$orb = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : '';
		$birthTimeUnknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$rectificationChart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : '';
		$aspectFilter = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new DateTimeImmutable( $datetime, $tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		if (in_array('chart', $filter)) {
			$method = new NatalChart($client);
			$chart = $method->process($location, $datetime, $houseSystem, $orb, $birthTimeUnknown, $rectificationChart, $aspectFilter);
		}
		if (in_array('aspect-chart', $filter)) {
			$method = new NatalAspectChart( $client );
			$aspectChart = $method->process($location, $datetime, $houseSystem, $orb, $birthTimeUnknown, $rectificationChart, $aspectFilter);
		}
		if (in_array('planet-positions', $filter)) {
			$method = new NatalPlanetPositions( $client );
			$result = $method->process($location, $datetime, $houseSystem, $orb, $birthTimeUnknown, $rectificationChart);
		}
		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/natal-chart',
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
