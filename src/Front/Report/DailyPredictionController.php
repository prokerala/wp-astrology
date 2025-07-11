<?php
/**
 * Chart controller.
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

use Prokerala\Api\Horoscope\Service\DailyPredictionAdvanced;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Daily Prediction Controller.
 *
 * @since   1.1.0
 */
class DailyPredictionController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

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
	public function render_form( $options = [] ) {
		return '';
	}

	/**
	 * Process result and render result.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$tz = $this->get_timezone();

		$day  = $options['day'] ? $options['day'] : $this->get_post_input( 'day' );
		$sign = $options['sign'] ? $options['sign'] : $this->get_post_input( 'sign' );
		$type = $options['type'] ? $options['type'] : $this->get_post_input( 'type' );

		$datetime = new \DateTimeImmutable( $day, $tz );

		$result = $this->load_predictions( $datetime, $sign, $type );

		return $this->render(
			'result/daily-prediction',
			[
				'result'  => $result,
				'options' => $this->get_options(),
			]
		);
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.1.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults() {
		return $this->getCommonAttributeDefaults() + [
			'type' => 'general',
			'sign' => 'all',
			'day'  => 'today',
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
		return true;
	}

	/**
	 * Load prediction for the requested day from, updating cache if the value is not yet cached.
	 *
	 * @since 1.4.5
	 *
	 * @param \DateTimeImmutable $datetime Datetime to load prediction.
	 * @param string             $sign Zodiac sign to fetch load. Empty to retrieve for all signs.
	 * @param string             $type Prediction type to fetch load.
	 *
	 * @return array<string,mixed>
	 */
	private function load_predictions( \DateTimeImmutable $datetime, string $sign, string $type ) {
		$data = get_transient( 'astrology_daily_prediction_' . $sign . '_' . $type . '_' . $datetime->format( 'Y_m_d' ) );

		if ( ! $data ) {
			$data = [];
		}

		return $data ? $data : $this->fetch_prediction( $datetime, $sign, $type );
	}


	/**
	 * Fetch prediction from API server.
	 *
	 * @since 1.4.5
	 *
	 * @param \DateTimeImmutable $datetime Datetime to fetch prediction.
	 * @param string             $sign Zodiac sign to fetch load.
	 * @param string             $type Prediction type to fetch load.
	 *
	 * @return array<string,mixed>
	 */
	private function fetch_prediction( \DateTimeImmutable $datetime, string $sign, string $type ) {  // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$client           = $this->get_api_client();
		$method           = new DailyPredictionAdvanced( $client );
		$daily_prediction = $method->process( $datetime, $sign, $type )->getDailyPredictions();

		$data = get_transient( 'astrology_daily_prediction_' . $sign . '_' . $type . '_' . $datetime->format( 'Y_m_d' ) );

		if ( ! $data ) {
			$data = [];
		}

		foreach ( $daily_prediction as $prediction ) {
			$prediction_data = [];
			foreach ( $prediction->getPredictions() as $p ) {
				$prediction_data[] = [
					'type'      => $p->getType(),
					'text'      => $p->getPrediction(),
					'seek'      => $p->getSeek(),
					'challenge' => $p->getChallenge(),
					'insight'   => $p->getInsight(),
				];
			}
			$data[ $prediction->getSign()->getName() ] = [
				'id'         => $prediction->getSign()->getId(),
				'sign'       => $prediction->getSign()->getName(),
				'prediction' => $prediction_data,
			];
		}
		set_transient( 'astrology_daily_prediction_' . $sign . '_' . $type . '_' . $datetime->format( 'Y_m_d' ), $data, 259200 );

		return $data;
	}
}
