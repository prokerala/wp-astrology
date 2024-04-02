<?php
/**
 * Synastry Chart result.
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
	'chart'          => '/western-chart.tpl.php',
	'aspect-chart'   => '/western-aspect-chart.tpl.php',
	'planet-aspects' => '/western-planet-aspects.tpl.php',
];
?>
<div class="container">
	<table class="pk-astrology-table pk-astrology-table-responsive-sm">
		<tbody>

		<tr><th colspan="2" style="text-align: center"> Profile Details</th></tr>
		<tr><th colspan="2"> Profile A</th></tr>
		<tr>
			<td>Birth Date : </td>
			<td><?php echo $primary_birth_time->format( 'F d, Y l' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td>Birth Time :</td>
			<td>
				<?php echo $primary_birth_time->format( 'g:i A T (P)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo ( '1' === $primary_birth_time->format( 'I' ) ) ? ' (DST Applied)' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<tr>
			<td>Place of Birth: </td>
			<td><?php echo $primary_location_name;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td>Gender: </td>
			<td><?php echo $primary_gender;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr><th colspan="2"> Profile B</th></tr>
		<tr>
			<td>Birth Date : </td>
			<td><?php echo $secondary_birth_time->format( 'F d, Y l' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td>Birth Time :</td>
			<td>
				<?php echo $secondary_birth_time->format( 'g:i A T (P)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo ( '1' === $secondary_birth_time->format( 'I' ) ) ? ' (DST Applied)' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</td>
		</tr>
		<tr>
			<td>Place of Birth: </td>
			<td><?php echo $secondary_location_name;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td>Gender: </td>
			<td><?php echo $secondary_gender;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr><th colspan="2"> Chart Settings</th></tr>
		<tr>
			<td>House System:</td>
			<td><?php echo $house_system;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td>Chart Type:</td>
			<td><?php echo $chart_type;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<?php if ( 'Default' !== $orb ) : ?>
			<tr>
				<td>Orb:</td>
				<td><?php echo $orb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php foreach ( $display_options as $options ) : ?>
		<?php $this->render( __DIR__ . $result_renderer[ $options ] ); ?>
	<?php endforeach; ?>
</div>
