<?php
/**
 * Kundli result.
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

// phpcs:disable VariableAnalysis, WordPress.WP.GlobalVariablesOverride.Prohibited

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<div>
		<?php if ( ! empty( $result ) ) : ?>

			<?php $nakshatra_details = $result['nakshatra_details']; ?>
			<table class="pk-astrology-table">
				<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><th colspan=2">Nakshatra Details</th></tr>
				<?php foreach ( $result['nakshatra_details'] as $key => $kundli ) : ?>
					<?php $item = str_replace( '_', ' ', $key ); ?>
					<?php if ( in_array( $key, [ 'nakshatra', 'chandra_rasi', 'soorya_rasi' ], true ) ) : ?>
						<tr><th><?php echo ucwords( $item ); // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $kundli['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
						<tr><th><?php echo ucwords( $item ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Lord</th><td><?php echo "{$kundli['lord']['vedic_name']} ({$kundli['lord']['name']})"; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php elseif ( 'additional_info' === $key ) : ?>
						<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><th colspan=2">Additional Info</th></tr>
						<?php foreach ( $kundli as $index => $value ) : ?>
							<tr><th><?php echo ucwords( str_replace( '_', ' ', $index ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><th><?php echo ucwords( $item ); // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $kundli['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>

			<h3 class="text-black">Yoga Details</h3>
			<?php foreach ( $result['yoga_details'] as $data ) : ?>
				<h3><?php echo ( $data['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<p>
				<?php
				echo $data['description']; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
				</p>
				<?php if ( 'advanced' === $result_type ) : ?>
					<?php foreach ( $data['yogaList'] as $yogas ) : ?>
						<?php if ( $yogas['hasYoga'] ) : ?>
						<b><?php echo $yogas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></b>
						<p><?php echo $yogas['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="pk-astrology-alert pk-astrology-text-center  <?php echo $result['mangal_dosha']['has_dosha'] ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success'; ?>" >
				<?php echo $result['mangal_dosha']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<?php if ( 'advanced' === $result_type ) : ?>
				<?php if ( $result['mangal_dosha']['has_exception'] ) : ?>
					<h3>Exceptions</h3>
					<ul>
						<?php foreach ( $result['mangal_dosha']['exceptions'] as $exceptions ) : ?>
							<li><?php echo $exceptions; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="pk-astrology-dasha-periods">
				<?php foreach ( $result['dasha_periods'] as $mahadashas ) : ?>
					<h3>Anthardashas in <?php echo $mahadashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?> Mahadasha</h3>
					<div class="pk-astrology-row">
					<?php foreach ( $mahadashas['antardasha'] as $anthardashas ) : ?>
						<table class="pk-astrology-table pk-astrology-col-12 pk-astrology-col-md-6">
							<tr><th>AD</th><th>PD</th><th>Starts</th><th>Ends</th></tr>
						<?php foreach ( $anthardashas['pratyantardasha'] as $paryantradashas ) : ?>
						<tr>
							<td><?php echo $anthardashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $paryantradashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $paryantradashas['start']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $paryantradashas['end']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						</tr>
						<?php endforeach; ?>
						</table>
					<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				<p class="pk-astrology-text-small pk-astrology-text-right pk-astrology-text-danger"><span class="pk-astrology-text-danger">**</span> AD stands for Antardasha &  PD stands for Paryantra dasha</p>
				</div>
				<?php
			endif;
			endif;
		?>
	</div>
<?php
