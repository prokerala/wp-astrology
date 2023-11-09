<?php
/**
 * Inauspicious Period controller.
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
use Exception;
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
	public function __construct(array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render inauspicious-period form.
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
		$available_language = array_filter($report_language, fn ($val) => in_array($val, ['en', 'ml', 'ta', 'te', 'hi']));
		$dir = __DIR__ . "/../../Locale/$form_language.php";
		$translation_data = include $dir;

		return $this->render(
			'form/inauspicious-period',
			[
				'options'  => $options + $this->get_options(),
				'datetime' => new DateTimeImmutable( $datetime, $this->get_timezone() ),
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
		$datetime = $this->get_post_input( 'datetime');
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new DateTimeImmutable( $datetime, $tz );
		$method   = new InauspiciousPeriod( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$method->setTimeZone( $tz );
		$lang = $this->get_post_input('lang');

		$result_lang = match(true) {
			($options['form_language'] && !$lang) =>  $options['form_language'],
			!empty($lang) => $lang,
			default => 'en'
		};
		$result = $method->process( $location, $datetime, $result_lang );

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
				'selected_lang' => $options['form_lang'] ?? $result_lang
			]
		);
	}
}
