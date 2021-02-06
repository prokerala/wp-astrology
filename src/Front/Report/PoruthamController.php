<?php
/**
 * Porutham controller.
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

use Prokerala\Api\Astrology\Profile;
use Prokerala\Api\Astrology\Service\Porutham;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Porutham Form Controller.
 *
 * @since   1.0.0
 */
class PoruthamController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * PoruthamController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render porutham form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		return $this->render(
			'form/porutham',
			[
				'options'     => $options + $this->get_options(),
				'girl_dob'    => new \DateTimeImmutable( 'now', $this->get_timezone() ),
				'boy_dob'     => new \DateTimeImmutable( 'now', $this->get_timezone() ),
				'result_type' => 'basic',
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
		$tz            = $this->get_timezone();
		$client        = $this->get_api_client();
		$girl_location = $this->get_location( $tz, 'girl_' );
		$boy_location  = $this->get_location( $tz, 'boy_' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$girl_dob    = isset( $_POST['girl_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['girl_dob'] ) ) : '';
		$boy_dob     = isset( $_POST['boy_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['boy_dob'] ) ) : '';
		$system      = isset( $_POST['system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['system'] ) ) : '';
		$result_type = isset( $_POST['result_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['result_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$advanced = 'advanced' === $result_type;
		$girl_dob = new \DateTimeImmutable( $girl_dob, $tz );
		$boy_dob  = new \DateTimeImmutable( $boy_dob, $tz );

		$girl_profile = new Profile( $girl_location, $girl_dob );
		$boy_profile  = new Profile( $boy_location, $boy_dob );

		$method = new Porutham( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $girl_profile, $boy_profile, $system, $advanced );

		$compatibility_result = [];
		$girl_info            = $result->getGirlInfo();
		$girl_nakshatra       = $girl_info->getNakshatra();
		$girl_rasi            = $girl_info->getRasi();

		$boy_info      = $result->getBoyInfo();
		$boy_nakshatra = $boy_info->getNakshatra();
		$boy_rasi      = $boy_info->getRasi();

		$girl_nakshatra_lord = $girl_nakshatra->getLord();
		$boy_nakshatra_lord  = $boy_nakshatra->getLord();

		$girl_rasi_lord = $girl_rasi->getLord();
		$boy_rasi_lord  = $boy_rasi->getLord();

		$compatibility_result['girlInfo'] = [
			'nakshatra' => [
				'id'   => $girl_nakshatra->getId(),
				'name' => $girl_nakshatra->getName(),
				'pada' => $girl_nakshatra->getPada(),
				'lord' => [
					'id'        => $girl_nakshatra_lord->getId(),
					'name'      => $girl_nakshatra_lord->getName(),
					'vedicName' => $girl_nakshatra_lord->getVedicName(),
				],
			],
			'rasi'      => [
				'id'   => $girl_rasi->getId(),
				'name' => $girl_rasi->getName(),
				'lord' => [
					'id'        => $girl_rasi_lord->getId(),
					'name'      => $girl_rasi_lord->getName(),
					'vedicName' => $girl_rasi_lord->getVedicName(),
				],
			],
		];

		$compatibility_result['boyInfo']       = [
			'nakshatra' => [
				'id'   => $boy_nakshatra->getId(),
				'name' => $boy_nakshatra->getName(),
				'pada' => $boy_nakshatra->getPada(),
				'lord' => [
					'id'        => $boy_nakshatra_lord->getId(),
					'name'      => $boy_nakshatra_lord->getName(),
					'vedicName' => $boy_nakshatra_lord->getVedicName(),
				],
			],
			'rasi'      => [
				'id'   => $boy_rasi->getId(),
				'name' => $boy_rasi->getName(),
				'lord' => [
					'id'        => $boy_rasi_lord->getId(),
					'name'      => $boy_rasi_lord->getName(),
					'vedicName' => $boy_rasi_lord->getVedicName(),
				],
			],
		];
		$compatibility_result['maximumPoints'] = $result->getMaximumPoints();
		$compatibility_result['totalPoints']   = $result->getTotalPoints();
		$message                               = $result->getMessage();
		$compatibility_result['message']       = [
			'type'        => $message->getType(),
			'description' => $message->getDescription(),
		];

		$match_result = $result->getMatches();

		foreach ( $match_result as $match ) {
			$matches = [
				'id'          => $match->getId(),
				'name'        => $match->getName(),
				'hasPorutham' => $match->hasPorutham(),
			];
			if ( $advanced ) {
				$matches['poruthamStatus'] = $match->getPoruthamStatus();
				$matches['points']         = $match->getPoints();
				$matches['description']    = $match->getDescription();
			}
			$compatibility_result['matches'][] = $matches;
		}

		return $this->render(
			'result/porutham',
			[
				'result'      => $compatibility_result,
				'result_type' => $result_type,
				'girl_dob'    => $girl_dob,
				'boy_dob'     => $boy_dob,
				'options'     => $this->get_options(),
			]
		);
	}
}
