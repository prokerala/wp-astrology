<?php
/**
 * Daily Panchang controller.
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
use DateTimeZone;
use Exception;
use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Result\Panchang\AdvancedPanchang;
use Prokerala\Api\Astrology\Service\Panchang;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Daily Panchang Form Controller.
 *
 * @since   1.0.0
 */
class DailyPanchangController implements ReportControllerInterface {

	use ReportControllerTrait{
		get_attribute_defaults as getCommonAttributeDefaults;
	}
	use PanchangControllerTrait;

	private const REPORT_LANGUAGES = [
		'en', 'hi', 'ta', 'ml', 'te'
	];
 	/**
	 * PanchangController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct(array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render panchang form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string
	{
		return '';
	}

	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ): string
	{
		$tz       = $this->get_timezone();
		$result_type = $options['result_type'] ?: $this->get_post_input( 'result_type', 'basic' );
		$advanced = 'advanced' === $result_type;

		$lang = $this->get_post_language('lang', self::REPORT_LANGUAGES, $options['form_language']);

		$client   = $this->get_api_client();

		$method   = new Panchang( $client );

		$method->setTimeZone( $tz );

		$method->setAyanamsa( $options['ayanamsa']);

		$datetime = new DateTimeImmutable( $options['date'], $tz );
		$location = $this->get_location_from_shortcode( $options['coordinates'], $tz );

		$key = "astrology_daily_prediction_{$result_type}_{$options['date']}";

		$result = $this->load_cached_panchang_data($key) ;

		if (empty($result)) {
			$result = $method->process( $location, $datetime, $advanced, $lang );
			$this->cache_panchang_data($key, $result);
		}


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
				'selected_lang' => $lang,
				'title' => 'Daily Panchang',

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
	public function get_attribute_defaults(): array
	{
		return $this->getCommonAttributeDefaults() + [
				'date' => '',
				'ayanamsa' => 1,
				'coordinates' => '',
			];
	}


	/**
	 * Create location object from short code.
	 *
	 * @param DateTimeZone $tz Timezone.
	 * @param string $coordinates Render options.
	 * @return Location
	 *@since 1.0.0
	 *
	 */
	protected function get_location_from_shortcode(string $coordinates, DateTimeZone $tz ): Location
	{
		[$latitude, $longitude] = explode( ',', $coordinates );

		return new Location( (float)$latitude, (float)$longitude, 0, $tz );
	}

	private function load_cached_panchang_data(string $key)
	{
		return get_transient( $key )['panchang'];
	}

	private function cache_panchang_data(string $key, \Prokerala\Api\Astrology\Result\Panchang\Panchang|AdvancedPanchang $result): void
	{
		$data['panchang'] = $result;

		$current_time = current_time('timestamp');
		$end_of_day = strtotime('today midnight +1 day') - 1;
		$expiration_time = $end_of_day - $current_time;

		set_transient( $key, $data, $expiration_time );
	}

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function can_render_result(): bool
	{
		return true;
	}
}