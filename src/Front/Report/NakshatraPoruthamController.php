<?php
/**
 * NakshatraPorutham controller.
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

use Prokerala\Api\Astrology\Service\NakshatraPorutham;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;
use Prokerala\Api\Astrology\NakshatraProfile;

/**
 * NakshatraPorutham Form Controller.
 *
 * @since   1.0.0
 */
class NakshatraPoruthamController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * NakhatraList
	 */

	const NAKSHATA_LIST = [
		'Ashwini',
		'Bharani',
		'Krithika',
		'Rohini',
		'Mrigashirsha',
		'Ardra',
		'Punarvasu',
		'Pushya',
		'Ashlesha',
		'Magha',
		'Purva Phalguni',
		'Uttara Phalguni',
		'Hasta',
		'Chitra',
		'Swati',
		'Vishaka',
		'Anuradha',
		'Jyeshta',
		'Moola',
		'Purva Ashadha',
		'Uttara Ashadha',
		'Shravana',
		'Dhanishta',
		'Shatabhisha',
		'Purva Bhadrapada',
		'Uttara Bhadrapada',
		'Revati',
	];

	/**
	 * NakshatraPoruthamController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render nakshatra-porutham form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		return $this->render(
			'form/nakshatra-porutham',
			[
				'options'        => $options + $this->get_options(),
				'nakshatra_list' => self::NAKSHATA_LIST,
				'result_type'    => 'basic',
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client = $this->get_api_client();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$girl_nakshatra      = isset( $_POST['girl_nakshatra'] ) ? (int) $_POST['girl_nakshatra'] : '';
		$boy_nakshatra       = isset( $_POST['boy_nakshatra'] ) ? (int) $_POST['boy_nakshatra'] : '';
		$girl_nakshatra_pada = isset( $_POST['girl_nakshatra_pada'] ) ? (int) $_POST['girl_nakshatra_pada'] : '';
		$boy_nakshatra_pada  = isset( $_POST['boy_nakshatra_pada'] ) ? (int) $_POST['boy_nakshatra_pada'] : '';
		$result_type         = isset( $_POST['result_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['result_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$girl_profile = new NakshatraProfile( $girl_nakshatra, $girl_nakshatra_pada );
		$boy_profile  = new NakshatraProfile( $boy_nakshatra, $boy_nakshatra_pada );
		$advanced     = 'advanced' === $result_type;

		$method = new NakshatraPorutham( $client );
		$result = $method->process( $girl_profile, $boy_profile, $advanced );

		$compatibility_result = $this->get_compatibility_result( $result, $advanced );

		return $this->render(
			'result/nakshatra-porutham',
			[
				'result'      => $compatibility_result,
				'result_type' => $result_type,
				'options'     => $this->get_options(),
			]
		);
	}

	/**
	 * Get compatability result
	 *
	 * @param object $result API Result.
	 * @param int    $advanced Advanced Result.
	 * @return array
	 */
	private function get_compatibility_result( $result, $advanced ) {

		$compatibility_result = [];

		$compatibility_result['maximumPoint']  = $result->getMaximumPoints();
		$compatibility_result['ObtainedPoint'] = $result->getObtainedPoints();
		$message                               = $result->getMessage();
		$compatibility_result['message']       = [
			'type'        => $message->getType(),
			'description' => $message->getDescription(),
		];
		$compatibility_result['Matches']       = [];

		foreach ( $result->getMatches() as $idx => $match ) {
			$compatibility_result['Matches'][ $idx ] = [
				'id'          => $match->getId(),
				'name'        => $match->getName(),
				'hasPorutham' => $match->hasPorutham(),
			];
			if ( $advanced ) {
				$compatibility_result['Matches'][ $idx ]['poruthamStatus'] = $match->getPoruthamStatus();
				$compatibility_result['Matches'][ $idx ]['points']         = $match->getPoints();
				$compatibility_result['Matches'][ $idx ]['description']    = $match->getDescription();
			}
		}

		return $compatibility_result;
	}
}
