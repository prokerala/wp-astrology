<?php
/**
 * Panchang controller.
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

use Prokerala\Api\Astrology\Service\Panchang;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Panchang Form Controller.
 *
 * @since   1.0.0
 */
class PanchangController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * PanchangController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render panchang form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @return string
	 */
	public function render_form() {
		return $this->render(
			'form/panchang',
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
		$method   = new Panchang( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $location, $datetime, $advanced );

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

		$panchang_details = $this->getPanchangDetails( $panchang );
		$panchang_result  = array_merge( $panchang_result, $panchang_details );

		$data['basic_info'] = $panchang_result;

		if ( $advanced ) {
			$data['auspicious_period']   = $this->getAdvancedInfo( $result->getAuspiciousPeriod() );
			$data['inauspicious_period'] = $this->getAdvancedInfo( $result->getInauspiciousPeriod() );
		}

		return $this->render(
			'result/panchang',
			[
				'result'      => $data,
				'result_type' => $result_type,
				'options'     => $this->get_options(),
			]
		);
	}

	/**
	 * Muhurat timing
	 *
	 * @param array<string,mixed> $muhurat muhuratdetails.
	 * @return array
	 */
	public function getAdvancedInfo( $muhurat ) {
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
	public function getPanchangDetails( $panchang ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
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

}
