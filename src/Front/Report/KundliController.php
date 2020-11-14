<?php
/**
 * Kundli controller.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2020 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2020 Ennexa Technologies Private Limited
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

use Prokerala\Api\Astrology\Service\Kundli;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Kundli Form Controller.
 *
 * @since   1.0.0
 */
class KundliController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * KundliController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render kundli form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @return string
	 */
	public function render_form() {
		return $this->render(
			'form/kundli',
			[
				'options'     => $this->get_options(),
				'datetime'    => new \DateTimeImmutable( 'now', $this->get_timezone() ),
				'result_type' => 'basic',
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @return string
	 */
	public function process() {
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime    = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		$result_type = isset( $_POST['result_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['result_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$advanced = 'advanced' === $result_type;

		$method = new Kundli( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result            = $method->process( $location, $datetime, $advanced );
		$nakshatra_details = $result->getNakshatraDetails();
		$nakshatra         = $nakshatra_details->getNakshatra();
		$nakshatra_lord    = $nakshatra->getLord();

		$chandra_rasi      = $nakshatra_details->getChandraRasi();
		$chandra_rasi_lord = $chandra_rasi->getLord();
		$soorya_rasi       = $nakshatra_details->getSooryaRasi();
		$soorya_rasi_lord  = $soorya_rasi->getLord();
		$zodiac            = $nakshatra_details->getZodiac();
		$additional_info   = $nakshatra_details->getAdditionalInfo();
		$mangal_dosha      = $result->getMangalDosha();
		$yoga_details      = $result->getYogaDetails();

		$kundli_result = [
			'nakshatra_details' => [
				'nakshatra'       => [
					'id'   => $nakshatra->getId(),
					'name' => $nakshatra->getName(),
					'lord' => [
						'id'         => $nakshatra_lord->getId(),
						'name'       => $nakshatra_lord->getName(),
						'vedic_name' => $nakshatra_lord->getVedicName(),
					],
					'pada' => $nakshatra->getPada(),
				],
				'chandra_rasi'    => [
					'id'   => $chandra_rasi->getId(),
					'name' => $chandra_rasi->getName(),
					'lord' => [
						'id'         => $chandra_rasi_lord->getId(),
						'name'       => $chandra_rasi_lord->getName(),
						'vedic_name' => $chandra_rasi_lord->getVedicName(),
					],
				],
				'soorya_rasi'     => [
					'id'   => $soorya_rasi->getId(),
					'name' => $soorya_rasi->getName(),
					'lord' => [
						'id'         => $soorya_rasi_lord->getId(),
						'name'       => $soorya_rasi_lord->getName(),
						'vedic_name' => $soorya_rasi_lord->getVedicName(),
					],
				],
				'zodiac'          => [
					'id'   => $zodiac->getId(),
					'name' => $zodiac->getName(),
				],
				'additional_info' => [
					'deity'          => $additional_info->getDeity(),
					'ganam'          => $additional_info->getGanam(),
					'symbol'         => $additional_info->getSymbol(),
					'animal_sign'    => $additional_info->getAnimalsign(),
					'nadi'           => $additional_info->getNadi(),
					'color'          => $additional_info->getColor(),
					'best_direction' => $additional_info->getBestdirection(),
					'syllables'      => $additional_info->getSyllables(),
					'birth_stone'    => $additional_info->getBirthstone(),
					'gender'         => $additional_info->getGender(),
					'planet'         => $additional_info->getPlanet(),
					'enemy_yoni'     => $additional_info->getEnemyYoni(),
				],
			],
			'mangal_dosha'      => [
				'has_dosha'   => $mangal_dosha->hasDosha(),
				'description' => $mangal_dosha->getDescription(),
			],
		];

		if ( $advanced ) {
			$kundli_advanced_info = $this->getAdvancedInfo( $result );
			$kundli_result        = array_merge( $kundli_result, $kundli_advanced_info );

		} else {
			$yoga_detail_result = [];
			foreach ( $yoga_details as $details ) {
				$yoga_detail_result[] = [
					'name'        => $details->getName(),
					'description' => $details->getDescription(),
				];
			}
			$kundli_result['yoga_details'] = $yoga_detail_result;
		}

		return $this->render(
			'result/kundli',
			[
				'result'      => $kundli_result,
				'result_type' => $result_type,
				'options'     => $this->get_options(),
			]
		);
	}

	/**
	 * Advanced result.
	 *
	 * @param array<string,mixed> $result kundlimatching result.
	 * @return array
	 */
	public function getAdvancedInfo( $result ) {
		$mangal_dosha                  = $result->getMangalDosha();
		$yoga_details                  = $result->getYogaDetails();
		$kundli_result                 = [];
		$kundli_result['mangal_dosha'] = [
			'has_dosha'     => $mangal_dosha->hasDosha(),
			'description'   => $mangal_dosha->getDescription(),
			'has_exception' => $mangal_dosha->hasException(),
			'type'          => $mangal_dosha->getType(),
			'exceptions'    => $mangal_dosha->getExceptions(),
		];

		$yoga_detail_result = [];

		foreach ( $yoga_details as $details ) {
			$yoga_list = $details->getYogaList();
			$yogas     = [];
			foreach ( $yoga_list as $yoga ) {
				$yogas[] = [
					'name'        => $yoga->getName(),
					'hasYoga'     => $yoga->hasYoga(),
					'description' => $yoga->getDescription(),
				];
			}
			$yoga_detail_result[] = [
				'name'        => $details->getName(),
				'description' => $details->getDescription(),
				'yogaList'    => $yogas,
			];
		}

		$kundli_result['yoga_details']  = $yoga_detail_result;
		$kundli_result['dasha_periods'] = $this->getDashaPeriodsDetails( $result->getDashaPeriods() );

		return $kundli_result;
	}

	/**
	 * Dasha period details.
	 *
	 * @param array $dasha_periods dashaperiods.
	 * @return array
	 */
	public function getDashaPeriodsDetails( $dasha_periods ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
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
