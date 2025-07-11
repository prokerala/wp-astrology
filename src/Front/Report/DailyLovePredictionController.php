<?php
/**
 * Daily Love Prediction controller.
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

use Prokerala\Api\Horoscope\Service\DailyLovePrediction;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Daily Love Prediction Controller.
 *
 * @since   1.4.5
 */
class DailyLovePredictionController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	const SIGNS = [
		'aries',
		'taurus',
		'gemini',
		'cancer',
		'leo',
		'virgo',
		'libra',
		'scorpio',
		'sagittarius',
		'capricorn',
		'aquarius',
		'pisces',
	];

	/**
	 * ChartController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render chart form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) { // phpcs:ignore  Generic.Metrics.CyclomaticComplexity.TooHigh

		$date     = $options['date'] ? $options['date'] : $this->get_post_input( 'date', 'today' );
		$sign_one = $options['sign_one'] ? $options['sign_one'] : $this->get_post_input( 'sign_one', 'aries' );
		$sign_two = $options['sign_two'] ? $options['sign_two'] : $this->get_post_input( 'sign_two', 'aries' );

		return $this->render(
			'form/daily-love-prediction',
			[
				'options'  => $this->get_options(),
				'signs'    => self::SIGNS,
				'sign_one' => $sign_one,
				'sign_two' => $sign_two,
				'day'      => $date,
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
	public function process( $options = [] ) { // phpcs:ignore  Generic.Metrics.CyclomaticComplexity.TooHigh
		$tz = $this->get_timezone();

		$date     = $options['date'] ? $options['date'] : $this->get_post_input( 'date', 'today' );
		$sign_one = $options['sign_one'] ? $options['sign_one'] : $this->get_post_input( 'sign_one', 'aries' );
		$sign_two = $options['sign_two'] ? $options['sign_two'] : $this->get_post_input( 'sign_two', 'aries' );
		$datetime = new \DateTimeImmutable( $date, $tz );

		$result = $this->load_predictions( $datetime, $sign_one, $sign_two );

		return $this->render(
			'result/daily-love-prediction',
			[
				'result'  => $result,
				'options' => $this->get_options(),
			]
		);
	}
	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.4.5
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults() {
		return $this->getCommonAttributeDefaults() + [
			'sign_one' => '',
			'sign_two' => '',
			'date'     => '',
		];
	}

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.1.0
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return bool
	 */
	public function can_render_result( $atts ) {
		return (
			isset( $atts['date'] ) || isset( $atts['sign_one'] ) || isset( $atts['sign_two'] )
			|| ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' === wp_unslash( $_SERVER['REQUEST_METHOD'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);
	}

	/**
	 * Load prediction for the requested day from, updating cache if the value is not yet cached.
	 *
	 * @since 1.4.5
	 *
	 * @param \DateTimeInterface $datetime Datetime to load prediction.
	 * @param string             $sign_one Zodiac sign to fetch load.
	 * @param string             $sign_two Zodiac sign to fetch load.
	 *
	 * @return array<string,mixed>
	 */
	private function load_predictions( $datetime, $sign_one, $sign_two ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$data = get_transient( $this->generateKey( $sign_one, $sign_two, $datetime ) );

		if ( ! $data ) {
			$data = [];
		}

		return $data ? $data : $this->fetch_prediction( $datetime, $sign_one, $sign_two );
	}

	/**
	 * Fetch prediction from API server.
	 *
	 * @since 1.4.5
	 *
	 * @param \DateTimeInterface $datetime Datetime to fetch prediction.
	 * @param string             $sign_one Zodiac sign to fetch load.
	 * @param string             $sign_two Zodiac sign to fetch load.
	 *
	 * @return array<string,mixed>
	 */
	private function fetch_prediction( $datetime, $sign_one, $sign_two ) {
		$client            = $this->get_api_client();
		$method            = new DailyLovePrediction( $client );
		$daily_predictiton = $method->process( $datetime, $sign_one, $sign_two )->getDailyLovePredictions();

		$data = get_transient( $this->generateKey( $sign_one, $sign_two, $datetime ) );
		if ( ! $data ) {
			$data = [];
		}

		foreach ( $daily_predictiton as $predictions ) {
			$data[ $predictions->getSignOne()->getName() ][ $predictions->getSignTwo()->getName() ] = [
				'combination'   => $predictions->getSignCombination(),
				'sign_one_id'   => $predictions->getSignOne()->getId(),
				'sign_one_name' => $predictions->getSignOne()->getName(),
				'sign_two_id'   => $predictions->getSignTwo()->getId(),
				'sign_two_name' => $predictions->getSignTwo()->getName(),
				'prediction'    => $predictions->getPrediction(),
			];
		}

		set_transient( $this->generateKey( $sign_one, $sign_two, $datetime ), $data, 259200 );

		return $data;
	}

	/**
	 * Generate prediction key for caching..
	 *
	 * @since 1.4.5
	 *
	 * @param \DateTimeInterface $datetime Datetime to fetch prediction.
	 * @param string             $sign_one Zodiac sign to fetch load.
	 * @param string             $sign_two Zodiac sign to fetch load.
	 *
	 * @return string
	 */
	public function generateKey( $datetime, $sign_one, $sign_two ): string {
		$indices = [
			$sign_one => array_search( $sign_one, self::SIGNS, true ),
			$sign_two => array_search( $sign_two, self::SIGNS, true ),
		];

		uksort(
			$indices,
			function ( $a, $b ) use ( $indices ) {
				return $indices[ $a ] - $indices[ $b ];
			}
		);

		$ordered_signs = array_keys( $indices );

		return 'astrology_daily_love_prediction_' . implode( '_', $ordered_signs ) . '_' . $datetime->format( 'Y_m_d' );
	}
}
