<?php
/**
 * MangalDosha controller.
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

use Prokerala\Api\Astrology\Service\MangalDosha;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Chart Form Controller.
 *
 * @since   1.0.0
 */
class MangalDoshaController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * MangalDoshaController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render mangal-dosha form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		return $this->render(
			'form/mangal-dosha',
			[
				'options'     => $options + $this->get_options(),
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
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) {
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime    = isset( $_POST['datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['datetime'] ) ) : '';
		$result_type = isset( $_POST['result_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['result_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$advanced = 'advanced' === $result_type;
		$method   = new MangalDosha( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $location, $datetime, $advanced );

		$mangal_dosha_result = [];

		$mangal_dosha_result['has_mangal_dosha'] = $result->hasDosha();
		$mangal_dosha_result['description']      = $result->getDescription();

		if ( $advanced ) {
			$mangal_dosha_result['has_exception']     = $result->hasException();
			$mangal_dosha_result['mangal_dosha_type'] = $result->getType();

			$mangal_dosha_result['exceptions'] = $result->getExceptions();
			$mangal_dosha_result['remedies']   = $result->getRemedies();
		}

		return $this->render(
			'result/mangal-dosha',
			[
				'result'      => $mangal_dosha_result,
				'result_type' => $result_type,
				'options'     => $this->get_options(),
			]
		);
	}
}
