<?php
/**
 * Birth Details controller.
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

use Prokerala\Api\Astrology\Service\BirthDetails;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Numerology Form Controller.
 *
 * @since   1.0.0
 */
class NumerologyController implements ReportControllerInterface {

	use ReportControllerTrait;

	/**
	 * NumerologyController constructor
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
		$calculators = [
			'pythagorean' => [
				'attainment-number'        => 'Attainment Number',
				'balance-number'           => 'Balance Number',
				'birth-month-number'       => 'Birth Month Number',
				'birthday-number'          => 'Birthday Number',
				'bridge-number'            => 'Bridge Number',
				'capstone-number'          => 'Capstone Number',
				'challenge-number'         => 'Challenge Number',
				'cornerstone-number'       => 'Corner Stone Number',
				'destiny-number'           => 'Destiny Number',
				'expression-number'        => 'Expression Number',
				'hidden-passion-number'    => 'Hidden Passion Number',
				'inner-dream-number'       => 'Inner Dream Number',
				'karmic-debt-number'       => 'Karmic Debt Number',
				'life-path-number'         => 'Life Path Number',
				'maturity-number'          => 'Maturity Number',
				'personal-day-number'      => 'Personal Day Number',
				'personal-month-number'    => 'Personal Month Number',
				'personal-year-number'     => 'Personal Year Number',
				'personality-number'       => 'Personality Number',
				'pinnacle-number'          => 'Pinnacle Number',
				'rational-thought-number'  => 'Rational Thought Number',
				'soul-urge-number'         => 'Soul Urge Number',
				'subconscious-self-number' => 'Subconscious Self Number',
				'universal-day-number'     => 'Universal Day Number',
				'universal-month-number'   => 'Universal Month Number',
				'universal-year-number'    => 'Universal Year Number',
			],
			'chaldean'    => [
				'birth-number'                 => 'Birth Number',
				'daily-name-number'            => 'Daily Name Number',
				'identity-initial-code-number' => 'Identity Initial Code Number',
				'life-path-number'             => 'Life Path Number',
				'whole-name-number'            => 'Whole Name Number',
			],
		];

		$selected_system          = $options['system'];
		$selected_calculator_name = $calculators[ $selected_system ][ $options['calculator'] ];

		return $this->render(
			'form/numerology-birth-details',
			[
				'options'                  => $options + $this->get_options(),
				'datetime'                 => new \DateTimeImmutable( 'now', $this->get_timezone() ),
				'reference_year'           => ( new \DateTimeImmutable( 'now', $this->get_timezone() ) )->format( 'Y' ),
				'calculators'              => $calculators,
				'selected_system'          => $selected_system,
				'selected_calculator_name' => $selected_calculator_name,
				'selected_calculator'      => $options['calculator'],
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
		$tz     = $this->get_timezone();
		$client = $this->get_api_client();

		$calculator_class = [
			'pythagorean' => [
				'life-path-number'         => LifePathNumber::class,
				'capstone-number'          => CapStoneNumber::class,
				'personality-number'       => PersonalityNumber::class,
				'challenge-number'         => ChallengeNumber::class,
				'inner-dream-number'       => InnerDreamNumber::class,
				'personal-year-number'     => PersonalYearNumber::class,
				'expression-number'        => ExpressionNumber::class,
				'universal-month-number'   => UniversalMonthNumber::class,
				'personal-month-number'    => PersonalMonthNumber::class,
				'soul-urge-number'         => SoulUrgeNumber::class,
				'destiny-number'           => DestinyNumber::class,
				'attainment-number'        => AttainmentNumber::class,
				'birth-day-number'         => BirthDayNumber::class,
				'universal-day-number'     => UniversalDayNumber::class,
				'birth-month-number'       => BirthMonthNumber::class,
				'universal-year-number'    => UniversalYearNumber::class,
				'balance-number'           => BalanceNumber::class,
				'personal-day-number'      => PersonalDayNumber::class,
				'cornerstone-number'       => CornerStoneNumber::class,
				'subconscious-self-number' => SubconsciousSelfNumber::class,
				'maturity-number'          => MaturityNumber::class,
				'hidden-passion-number'    => HiddenPassionNumber::class,
				'rational-thought-number'  => RationalThoughtNumber::class,
				'pinnacle-number'          => PinnacleNumber::class,
				'karmic-debt-number'       => KarmicDebtNumber::class,
				'bridge-number'            => BridgeNumber::class,
			],
			'chaldean'    => [
				'birth-number'                 => BirthNumber::class,
				'daily-name-number'            => DailyNameNumber::class,
				'life-path-number'             => ChaldeanLifePathNumber::class,
				'identity-initial-code-number' => IdentityInitialCode::class,
				'whole-name-number'            => WholeNameNumber::class,
			],
		];

		$calculator_params = [
			'pythagorean' => [
				'date'                    => [
					'life-path-number',
					'birth-month-number',
					'universal-month-number',
					'birth-day-number',
					'universal-day-number',
					'universal-year-number',
					'challenge-number',
					'pinnacle-number',
					'life-cycle-number',
				],
				'date_and_reference_year' => [
					'personal-year-number',
					'personal-month-number',
					'personal-day-number',
				],
				'name'                    => [
					'capstone-number',
					'destiny-number',
					'expression-number',
					'hidden-passion-number',
					'balance-number',
					'subconscious-self-number',
					'soul-urge-number',
					'cornerstone-number',

				],
				'name_and_vowel'          => [
					'personality-number',
					'inner-dream-number',
				],
				'date_and_name'           => [
					'attainment-number',
					'maturity-number',
					'rational-thought-number',
					'karmic-debt-number',
					'bridge-number',
				],
			],
			'chaldean'    => [
				'date' => [
					'birth-number',
					'life-path-number',
				],
				'name' => [
					'identity-initial-code-number',
					'whole-name-number',
				],
			],
		];

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$date           = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['date'] ) ) : '';
		$system         = isset( $_POST['system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['system'] ) ) : '';
		$calculator     = isset( $_POST['calculator'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['calculator'] ) ) : '';
		$first_name     = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['first_name'] ) ) : '';
		$middle_name    = isset( $_POST['middle_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['middle_name'] ) ) : '';
		$last_name      = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['last_name'] ) ) : '';
		$reference_year = isset( $_POST['reference_year'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['reference_year'] ) ) : '';
		$vowel          = isset( $_POST['vowel'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['vowel'] ) ) : '';

		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$date   = new \DateTimeImmutable( $date, $tz );
		$method = new $calculator_class[ $system ][ $calculator ]( $client );

		if ( in_array( $calculator, $calculator_params[ $system ]['date'], true ) ) {
			$result = $method->process( $date );
		} elseif ( in_array( $calculator, $calculator_params[ $system ]['name'], true ) ) {
			$result = $method->process( $first_name, $middle_name, $last_name );
		} elseif ( in_array( $calculator, $calculator_params[ $system ]['date_and_name'], true ) ) {
			$result = $method->process( $date, $first_name, $middle_name, $last_name );
		} elseif ( in_array( $calculator, $calculator_params[ $system ]['name_and_vowel'], true ) ) {
			$result = $method->process( $first_name, $middle_name, $last_name, $vowel );
		} elseif ( in_array( $calculator, $calculator_params[ $system ]['date_and_reference_year'], true ) ) {
			$result = $method->process( $date, $reference_year );
		} else {
			throw new \Exception( 'Selected calculator not found' );
		}

		$data                    = [];
		$data['calculator_name'] = ucwords( str_replace( '-', ' ', $calculator ) );
		$data['calculator']      = $calculator;
		$data['result']          = $result;
		$data['first_name']      = $first_name;
		$data['middle_name']     = $middle_name;
		$data['last_name']       = $last_name;
		$data['date']            = $date;
		$data['vowel']           = $vowel;
		$data['reference_year']  = $reference_year;

		return $this->render(
			'result/numerology-result',
			[
				'result'  => $result,
				'data'    => $data,
				'options' => $this->get_options(),
			]
		);
	}
}
