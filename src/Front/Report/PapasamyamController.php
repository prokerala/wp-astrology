<?php
/**
 * Papasamyam controller.
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

use Prokerala\Api\Astrology\Service\Papasamyam;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Papasamyam Form Controller.
 *
 * @since   1.0.0
 */
class PapasamyamController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * PapasamyamController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render papasamyam form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @return string
	 */
	public function render_form() {
		return $this->render(
			'form/papasamyam',
			[
				'options'  => $this->get_options(),
				'datetime' => new \DateTimeImmutable( 'now', $this->get_timezone() ),
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
		$datetime = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$method   = new Papasamyam( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $location, $datetime );

		$papasamyam_result['total_points'] = $result->getTotalPoints();
		$papa_samyam                       = $result->getPapaSamyam();
		$papa_planets                      = $papa_samyam->getPapaPlanet();
		foreach ( $papa_planets as $idx => $papa_planet ) {
			$papasamyam_result['papaPlanet'][ $idx ]['name'] = $papa_planet->getName();
			$planet_doshas                                   = $papa_planet->getPlanetDosha();
			foreach ( $planet_doshas as $planet_dosha ) {
				$papasamyam_result['papaPlanet'][ $idx ]['planetDosha'][] = [
					'id'       => $planet_dosha->getId(),
					'name'     => $planet_dosha->getName(),
					'position' => $planet_dosha->getPosition(),
					'hasDosha' => $planet_dosha->hasDosha(),
				];
			}
		}

		return $this->render( 'result/papasamyam', [ 'result' => $papasamyam_result ] );
	}
}
