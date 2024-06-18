<?php
/**
 * Progression Chart result.
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

// phpcs:disable VariableAnalysis, WordPress.WP.GlobalVariablesOverride.Prohibited

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$result_renderer = [
	'chart'            => '/western-chart.tpl.php',
	'aspect-chart'     => '/western-aspect-chart.tpl.php',
	'planet-positions' => '/western-planet-position.tpl.php',
	'planet-aspects'   => '/western-planet-aspects.tpl.php',
];
?>
<div class="container">

	<table class="pk-astrology-table">
		<tbody>

		<tr><th colspan="2" style="text-align:center"> Profile Details</th></tr>
		<tr>
			<td><?php echo $translation_data['birth_date']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : </td>
			<td><?php echo $datetime->format( 'F d, Y l' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td><?php echo $translation_data['birth_time']; // phpcs:ignore WordPress.Security.EscapeOutput ?> :</td>
			<td>
				<?php echo $datetime->format( 'g:i A T (P)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo ( '1' === $datetime->format( 'I' ) ) ? ' (DST Applied)' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $translation_data['birth_place']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : </td>
			<td><?php echo $location_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td><?php echo $translation_data['gender']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : </td>
			<td><?php echo $gender;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td><?php echo $translation_data['progression_year']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : </td>
			<td><?php echo $progression_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td><?php echo $translation_data['progression_place']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : </td>
			<td><?php echo $progression_location_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td><?php echo $translation_data['house_system']; // phpcs:ignore WordPress.Security.EscapeOutput ?> :</td>
			<td><?php echo $house_system; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<?php if ( 'Default' !== $orb ) : ?>
			<tr>
				<td><?php echo $translation_data['orb']; // phpcs:ignore WordPress.Security.EscapeOutput ?> :</td>
				<td><?php echo $orb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php foreach ( $display_options as $options ) : ?>
		<?php if ( 'planet-positions' === $options ) : ?>
			<table class="table table-bordered pk-astrology-pad-xsmall">
				<tr>
					<th>Progression Year</th>
					<td><?php echo $progression_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				</tr>
				<tr>
					<th>Progression Date</th>
					<td><?php echo $progression_date->format( 'd M Y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				</tr>
			</table>
		<?php endif; ?>

		<?php $this->render( __DIR__ . $result_renderer[ $options ] ); ?>

		<?php if ( 'planet-aspects' === $options ) : ?>
			<!--            Progression - Natal Aspects table-->
			<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall">Progression - Natal Aspects</h3>
			<table class="table table-bordered">
				<tr>
					<th>Planet 1</th>
					<th>Aspect</th>
					<th>Planet 2</th>
					<th>Orb</th>
				</tr>
				<?php foreach ( $progression_natal_aspects as $aspect ) : ?>
					<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php foreach ( $progression_natal_aspects as $aspect ) : ?>
					<?php if ( ! in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
