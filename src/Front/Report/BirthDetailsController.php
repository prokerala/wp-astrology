<?php
/**
 * Birth Details controller.
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

use Prokerala\Api\Astrology\Service\BirthDetails;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Birthdetails Form Controller.
 *
 * @since   1.0.0
 */
class BirthDetailsController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * BirthdetailsController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render birthdetails form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		$datetime = $this->get_post_input( 'datetime', 'now' );

		return $this->render(
			'form/birth-details',
			[
				'options'  => $options + $this->get_options(),
				'datetime' => new \DateTimeImmutable( $datetime, $this->get_timezone() ),
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
	public function process( $options = [] ) {
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$method   = new BirthDetails( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $location, $datetime );

		$data = [];

		$additional_info      = $result->getAdditionalInfo();
		$data['nakshatra']    = $result->getNakshatra();
		$data['chandra_rasi'] = $result->getChandraRasi();
		$data['soorya_rasi']  = $result->getSooryaRasi();
		$data['zodiac']       = $result->getZodiac();

		$nakshatra_info_list = [ 'Deity', 'Ganam', 'Symbol', 'AnimalSign', 'Nadi', 'Color', 'BestDirection', 'Syllables', 'BirthStone', 'Gender', 'Planet', 'EnemyYoni' ];
		foreach ( $nakshatra_info_list as $info ) {
			$function                         = 'get' . $info;
			$data['additional_info'][ $info ] = $additional_info->{$function}();
		}

		return $this->render(
			'result/birth-details',
			[
				'result'  => $data,
				'options' => $this->get_options(),
			]
		);
	}
}
