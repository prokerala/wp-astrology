<?php
/**
 * Dasha Period controller.
 *
 * @package   Prokerala\WP\Astrology
 * @subpackage WordPress Plugin
 * @author    Prokerala<support+api@prokerala.com>
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
use Prokerala\Api\Astrology\Service\DashaPeriod;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Dasha Period Form Controller.
 *
 * @since   1.4.9
 */
class DashaPeriodController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
		'hi',
		'ta',
		'ml',
	];
	/**
	 * DashaPeriodController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render dasha period form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string {

		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/dasha-period',
			[
				'options'          => $options + $this->get_options(),
				'datetime'         => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'report_language'  => $report_language,
				'selected_lang'    => $form_language,
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
	public function process( $options = [] ): string { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$datetime         = $this->get_post_input( 'datetime' );
		$lang             = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );
		$datetime         = new DateTimeImmutable( $datetime, $tz );
		$translation_data = $this->get_localisation_data( $lang );
		$method           = new DashaPeriod( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result                        = $method->process( $location, $datetime, $lang );
		$dasha_period['dasha_periods'] = $this->getDashaPeriodsDetails( $result->getDashaPeriods() );

		return $this->render(
			'result/dasha-period',
			[
				'result'           => $dasha_period,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}

	/**
	 * Dasha Period details.
	 *
	 * @param array $dasha_periods dashaperiods.
	 * @return array
	 */
	public function getDashaPeriodsDetails( array $dasha_periods ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$dasha_period_result = [];
		foreach ( $dasha_periods as $dasha_period ) {
			$antardashas       = $dasha_period->getAntardasha();
			$antardasha_result = [];
			foreach ( $antardashas as $antardasha ) {
				$pratyantardashas       = $antardasha->getPratyantardasha();
				$pratyantardasha_result = [];
				foreach ( $pratyantardashas as $pratyantardasha ) {
					$pratyantardasha_result[] = [
						'id'    => $pratyantardasha->getId(),
						'name'  => $pratyantardasha->getName(),
						'start' => $pratyantardasha->getStart(),
						'end'   => $pratyantardasha->getEnd(),
					];
				}
				$antardasha_result[] = [
					'id'              => $antardasha->getId(),
					'name'            => $antardasha->getName(),
					'start'           => $antardasha->getStart(),
					'end'             => $antardasha->getEnd(),
					'pratyantardasha' => $pratyantardasha_result,
				];
			}
			$dasha_period_result[] = [
				'id'         => $dasha_period->getId(),
				'name'       => $dasha_period->getName(),
				'start'      => $dasha_period->getStart(),
				'end'        => $dasha_period->getEnd(),
				'antardasha' => $antardasha_result,
			];
		}
		return $dasha_period_result;
	}
}
