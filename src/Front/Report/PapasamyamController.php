<?php
/**
 * Papasamyam controller.
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

use Exception;
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
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string
	{
		$datetime = $this->get_post_input( 'datetime', 'now' );
		$form_language = in_array($options['form_language'], ['en', 'hi', 'ta', 'ml']) ? $options['form_language'] : 'en';
		$report_language = $options['report_language'] ? explode(',', $options['report_language']) : [];
		$available_language = array_filter($report_language, fn ($val) => in_array($val, ['en', 'ml', 'ta', 'hi']));
		$dir = __DIR__ . "/../../Locale/$form_language.php";
		$translation_data = include $dir;

		return $this->render(
			'form/papasamyam',
			[
				'options'  => $options + $this->get_options(),
				'datetime' => new \DateTimeImmutable( $datetime, $this->get_timezone() ),
				'selected_lang' => $form_language,
				'report_language' => $available_language,
				'translation_data' => $translation_data,
			]
		);
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
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime = $this->get_post_input( 'datetime', '' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$method   = new Papasamyam( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$lang = $this->get_post_input('lang');

		$result_lang = match(true) {
			($options['form_language'] && !$lang) =>  $options['form_language'],
			!empty($lang) => $lang,
			default => 'en'
		};
		$result_lang = in_array($result_lang, ['en', 'hi', 'ta', 'ml']) ? $result_lang : 'en';

		$result = $method->process( $location, $datetime, $result_lang );

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

		return $this->render(
			'result/papasamyam',
			[
				'result'  => $papasamyam_result,
				'options' => $this->get_options(),
				'selected_lang' => $options['form_lang'] ?? $result

			]
		);
	}
}
