<?php
/**
 * Western controller.
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
use Prokerala\Api\Astrology\Western\Service\AspectCharts\CompositeChart as CompositeAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\NatalChart as NatalAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\ProgressionChart as ProgressionAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SolarReturnChart as SolarReturnAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SynastryChart as SynastryAspectChart;
use Prokerala\Api\Astrology\Western\Service\Charts\CompositeChart;
use Prokerala\Api\Astrology\Western\Service\Charts\NatalChart;
use Prokerala\Api\Astrology\Western\Service\Charts\ProgressionChart;
use Prokerala\Api\Astrology\Western\Service\Charts\SolarReturnChart;
use Prokerala\Api\Astrology\Western\Service\Charts\SynastryChart;
use Prokerala\Api\Astrology\Western\Service\Charts\TransitChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\TransitChart as TransitAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\CompositeChart as CompositePlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\NatalChart as NatalPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\ProgressionChart as ProgressionPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SolarReturnChart as SolarReturnPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SynastryChart as SynastryPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\TransitChart as TransitPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Western Chart Form Controller.
 *
 * @since   1.2.0
 */
class WesternChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * WesternChartController constructor
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
			'report_type'     => 'natal-chart',
			'display_options' => 'chart',
			'coordinate'      => '',
		];
	}

	/**
	 * Render Western Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$report_type = $options['report_type'];

		return match ( $report_type ) {
			'natal-chart' => $this->getNatalChartForm( $options ),
			'transit-chart' => $this->getTransitChartForm( $options ),
			'progression-chart' => $this->getProgressionChartForm( $options ),
			'solar-return-chart' => $this->getSolarReturnChartForm( $options ),
			'synastry-chart' => $this->getSynastryChartForm( $options ),
			'composite-chart' => $this->getCompositeChartForm( $options ),
		};
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
		$report_type = $options['report_type'];

		return match ( $report_type ) {
			'natal-chart' => $this->getNatalChartProcess( $options ),
			'transit-chart' => $this->getTransitChartProcess( $options ),
			'progression-chart' => $this->getProgressionChartProcess( $options ),
			'solar-return-chart' => $this->getSolarReturnChartProcess( $options ),
			'synastry-chart' => $this->getSynastryChartProcess( $options ),
			'composite-chart' => $this->getCompositeChartProcess( $options ),
		};
	}

	/**
	 * Render Natal Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getNatalChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
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
	 * Process result and render Natal result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getNatalChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );

		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$planet_positions = in_array( 'planet-positions', $display_options, true );
		$planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new NatalChart( $client );
			$chart  = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new NatalAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $planet_positions || $planet_aspects ) {
			$method = new NatalPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart );
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/natal-chart',
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

	/**
	 * Render Transit Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getTransitChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'transit_datetime'    => $datetime,
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
	 * Process result and render transit result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getTransitChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$transit_tz       = $this->get_timezone( 'current_' );
		$transit_location = $this->get_location( $transit_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$transit_datetime   = isset( $_POST['transit_datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$transit_datetime = new DateTimeImmutable( $transit_datetime, $transit_tz );
		$lang             = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

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

	/**
	 * Render Progression Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getProgressionChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$progression_year   = isset( $_POST['progression_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['progression_year'] ) ) : $datetime->format( 'Y' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
				'progression_year'    => $progression_year,
			]
		);
	}

	/**
	 * Process result and render Progression result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getProgressionChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz                   = $this->get_timezone();
		$client               = $this->get_api_client();
		$location             = $this->get_location( $tz );
		$progression_tz       = $this->get_timezone( 'current_' );
		$progression_location = $this->get_location( $progression_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$progression_year   = isset( $_POST['progression_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['progression_year'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$planet_positions = in_array( 'planet-positions', $display_options, true );
		$planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new ProgressionChart( $client );
			$chart  = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new ProgressionAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $planet_positions || $planet_aspects ) {
			$method = new ProgressionPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart );
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/progression-chart',
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

	/**
	 * Render Solar Return Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSolarReturnChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$solar_return_year  = isset( $_POST['solar_return_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['solar_return_year'] ) ) : $datetime->format( 'Y' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
				'solar_return_year'   => $solar_return_year,
			]
		);
	}

	/**
	 * Process result and render solar return result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSolarReturnChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$current_tz       = $this->get_timezone( 'current_' );
		$current_location = $this->get_location( $current_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$solar_return_year  = isset( $_POST['solar_return_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['solar_return_year'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$planet_positions = in_array( 'planet-positions', $display_options, true );
		$planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new SolarReturnChart( $client );
			$chart  = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new SolarReturnAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $planet_positions || $planet_aspects ) {
			$method = new SolarReturnPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart );
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/solar-return-chart',
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

	/**
	 * Render Synastry Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSynastryChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$chart_type                   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : 'zodiac-contact-chart';
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'                      => $options + $this->get_options(),
				'primary_birth_time'           => $datetime->modify( '-25 years' ),
				'secondary_birth_time'         => $datetime->modify( '-20 years' ),
				'aspect_filter'                => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
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
	 * Process result and render Synastry result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSynastryChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client                 = $this->get_api_client();
		$primary_tz             = $this->get_timezone( 'partner_a_' );
		$primary_birth_location = $this->get_location( $primary_tz, 'partner_a_' );

		$secondary_tz             = $this->get_timezone( 'partner_b_' );
		$secondary_birth_location = $this->get_location( $secondary_tz, 'partner_b_' );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondary_birth_time = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = new DateTimeImmutable( $primary_birth_time, $primary_tz );
		$secondary_birth_time = new DateTimeImmutable( $secondary_birth_time, $secondary_tz );
		[
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		]                     = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$chart_type                   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new SynastryChart( $client );
			$chart  = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}

		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new SynastryAspectChart( $client );
			$aspect_chart = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'planet-aspects', $display_options, true ) ) {
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

	/**
	 * Render Composite Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getCompositeChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );

		$transit_datetime = $datetime;
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'                      => $options + $this->get_options(),
				'primary_birth_time'           => $datetime->modify( '-25 years' ),
				'secondary_birth_time'         => $datetime->modify( '-20 years' ),
				'aspect_filter'                => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'                 => $house_system,
				'primary_birth_time_unknown'   => $primary_birth_time_unknown,
				'secondary_birth_time_unknown' => $secondary_birth_time_unknown,
				'rectification_chart'          => $rectification_chart,
				'transit_datetime'             => $transit_datetime,
				'orb'                          => $orb,
				'selected_lang'                => $form_language,
				'report_language'              => $report_language,
				'translation_data'             => $translation_data,
			]
		);
	}

	/**
	 * Process result and render Composite result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getCompositeChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client                 = $this->get_api_client();
		$primary_tz             = $this->get_timezone( 'partner_a_' );
		$primary_birth_location = $this->get_location( $primary_tz, 'partner_a_' );

		$secondary_tz             = $this->get_timezone( 'partner_b_' );
		$secondary_birth_location = $this->get_location( $secondary_tz, 'partner_b_' );

		$transit_tz       = $this->get_timezone( 'current_' );
		$current_location = $this->get_location( $transit_tz );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondary_birth_time = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = new DateTimeImmutable( $primary_birth_time, $primary_tz );
		$secondary_birth_time = new DateTimeImmutable( $secondary_birth_time, $secondary_tz );

		[
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$transit_datetime             = isset( $_POST['transit_datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$transit_datetime = new DateTimeImmutable( $transit_datetime, $transit_tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$planet_positions = in_array( 'planet-positions', $display_options, true );
		$planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new CompositeChart( $client );
			$chart  = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new CompositeAspectChart( $client );
			$aspect_chart = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $planet_positions || $planet_aspects ) {
			$method = new CompositePlanetPositions( $client );
			$result = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart );
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/composite-chart',
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


	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return array
	 */
	private function getCommonInputValues( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		$display_options = explode( ',', $options['display_options'] );
		if ( in_array( 'all', $display_options, true ) ) {
			$display_options = [ 'chart', 'aspect-chart', 'planet-positions', 'planet-aspects' ];
		}
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$aspect_filter       = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : 'major';
		$house_system        = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : 'placidus';
		$orb                 = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : 'default';
		$rectification_chart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : 'flat-chart';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return [
			'datetime'            => new DateTimeImmutable( $datetime, $this->get_timezone() ),
			'form_language'       => $form_language,
			'report_language'     => $report_language,
			'translation_data'    => $translation_data,
			'display_options'     => $display_options,
			'aspect_filter'       => $aspect_filter,
			'house_system'        => $house_system,
			'orb'                 => $orb,
			'rectification_chart' => $rectification_chart,
		];
	}
}
