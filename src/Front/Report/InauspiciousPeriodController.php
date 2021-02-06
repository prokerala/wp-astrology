<?php
/**
 * Inauspicious Period controller.
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

use Prokerala\Api\Astrology\Service\InauspiciousPeriod;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Inauspicious Period Form Controller.
 *
 * @since   1.0.0
 */
class InauspiciousPeriodController implements ReportControllerInterface {


	use ReportControllerTrait;

	/**
	 *  InauspiciousPeriodController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render inauspicious-period form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		return $this->render(
			'form/inauspicious-period',
			[
				'options'  => $options + $this->get_options(),
				'datetime' => new \DateTimeImmutable( 'now', $this->get_timezone() ),
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @return string
	 * @throws \Exception On render failure.
	 */
	public function process() {
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$method   = new InauspiciousPeriod( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $location, $datetime );

		$data = [];

		foreach ( $result->getMuhurat() as $idx => $muhurat ) {
			$data[ $idx ] = [
				'id'   => $muhurat->getId(),
				'name' => $muhurat->getName(),
				'type' => $muhurat->getType(),
			];
			foreach ( $muhurat->getPeriod() as $period ) {
				$data[ $idx ]['period'][] = [
					'start' => $period->getStart(),
					'end'   => $period->getEnd(),
				];
			}
		}

		return $this->render(
			'result/inauspicious-period',
			[
				'result'  => $data,
				'options' => $this->get_options(),
			]
		);
	}
}
