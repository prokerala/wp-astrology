<?php
/**
 * Re-usable methods for Panchang controllers.
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

use Prokerala\Api\Astrology\Result\Panchang\AdvancedPanchang;
use Prokerala\Api\Astrology\Result\Panchang\Panchang as BasicPanchang;

trait PanchangControllerTrait {


	/**
	 * Muhurat timing
	 *
	 * @param array<string,mixed> $muhurat muhuratdetails.
	 * @return array
	 */
	public function get_advanced_info( array $muhurat ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$muhurat_details = [];
		foreach ( $muhurat as $data ) {
			$field   = $data->getName();
			$periods = $data->getPeriod();
			foreach ( $periods as $period ) {
				$muhurat_details[ $field ][] = [
					'start' => $period->getStart(),
					'end'   => $period->getEnd(),
				];
			}
		}

		return $muhurat_details;
	}

	/**
	 * Panchang Details
	 *
	 * @param array<string,mixed> $panchang panchang data.
	 * @return array
	 */
	public function get_panchang_details( array $panchang ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$data_list       = [ 'Nakshatra', 'Tithi', 'Karana', 'Yoga' ];
		$panchang_result = [];

		foreach ( $data_list as $key ) {
			foreach ( $panchang[ $key ] as $idx => $data ) {
				$panchang_result[ $key ][ $idx ] = [
					'id'    => $data->getId(),
					'name'  => $data->getName(),
					'start' => $data->getStart(),
					'end'   => $data->getEnd(),
				];
				if ( 'Nakshatra' === $key ) {
					$panchang_result[ $key ][ $idx ]['nakshatra_lord'] = $data->getLord();
				} elseif ( 'Tithi' === $key ) {
					$panchang_result[ $key ][ $idx ]['paksha'] = $data->getPaksha();
				}
			}
		}

		return $panchang_result;
	}


	/**
	 * Format result to display in template.
	 *
	 * @since 1.2.0
	 *
	 * @param BasicPanchang|AdvancedPanchang $result Result data to be processed.
	 * @param bool                           $advanced Result type.
	 *
	 * @return array
	 */
	private function process_result( BasicPanchang|AdvancedPanchang $result, bool $advanced ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$panchang_result = [
			'sunrise'  => $result->getSunrise(),
			'sunset'   => $result->getSunset(),
			'moonrise' => $result->getMoonrise(),
			'moonset'  => $result->getMoonset(),
			'vaara'    => $result->getVaara(),
		];

		$panchang              = [];
		$panchang['Nakshatra'] = $result->getNakshatra();
		$panchang['Tithi']     = $result->getTithi();
		$panchang['Karana']    = $result->getKarana();
		$panchang['Yoga']      = $result->getYoga();

		$panchang_details = $this->get_panchang_details( $panchang );
		$panchang_result  = array_merge( $panchang_result, $panchang_details );

		$data['basic_info'] = $panchang_result;

		if ( $advanced ) {
			$data['auspicious_period']   = $this->get_advanced_info( $result->getAuspiciousPeriod() );
			$data['inauspicious_period'] = $this->get_advanced_info( $result->getInauspiciousPeriod() );
		}

		return $data;
	}
}
