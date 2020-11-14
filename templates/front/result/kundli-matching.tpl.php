<?php
/**
 * Kundli Matching result.
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
		<h3>Birth Details</h3>
		<table class="pk-astrology-table">
			<tr class="pk-astrology-bg-secondary">
				<th>#</th>
				<th>Details of Girl</th>
				<th>Details of Boy</th>
			</tr>
			<tr>
				<th>Date Of Birth</th>
				<td><?php echo $girl_dob->format( 'F d, Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				<td><?php echo $boy_dob->format( 'F d, Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			</tr>
			<?php foreach ( $result['girlInfo'] as $key => $info ) : ?>
				<?php if ( in_array( $key, [ 'nakshatra', 'rasi' ], true ) ) : ?>
					<?php foreach ( $info as $item => $item_vale ) : ?>
						<?php
						if ( 'id' === $item ) {
							continue;
						}
						?>
						<?php if ( 'lord' === $item ) : ?>
							<tr>
								<td><b><?php echo ucwords( $key . ' ' . $item ); // phpcs:ignore WordPress.Security.EscapeOutput ?></b></td>
								<td><?php echo "{$item_vale['vedicName']} ({$item_vale['name']})"; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
								<td><?php echo "{$result['boyInfo'][$key][$item]['vedicName']} ({$result['boyInfo'][$key][$item]['name']})"; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							</tr>
						<?php else : ?>
							<tr>
								<td><b><?php echo ucwords( $key . ' ' . $item ); // phpcs:ignore WordPress.Security.EscapeOutput ?></b></td>
								<td><?php echo $item_vale; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
								<td><?php echo $result['boyInfo'][ $key ][ $item ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>

		<h3>Guna Milan Details</h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<thead class="pk-astrology-bg-secondary">
			<tr >
				<th>#</th>
				<th>Guna</th>
				<th>Girl</th>
				<th>Boy</th>
				<?php if ( 'advanced' === $result_type ) : ?>
					<th>Maximum Points</th>
					<th>Obtained Points</th>
				<?php endif; ?>
			</tr>
			</thead>
			<?php if ( 'advanced' === $result_type ) : ?>
				<?php foreach ( $result['gunaMilan']['guna'] as $data ) : ?>
					<tr>
						<td><?php echo $data['id']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><b><?php echo $data['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?> Koot</b></td>
						<td><?php echo $data['girlKoot']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['boyKoot']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['maximumPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['obtainedPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<?php $count = 1; foreach ( $result['girlInfo']['koot'] as $guna => $data ) : ?>
					<?php
					$guna_koot = preg_replace( '/(?<!\ )[A-Z]/', ' $0', $guna );
					$guna_koot = ucwords( $guna_koot );
					?>
					<tr>
						<td><?php echo $count; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><b><?php echo $guna_koot; // phpcs:ignore WordPress.Security.EscapeOutput ?> Koot</b></td>
						<td><?php echo $data; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $result['boyInfo']['koot'][ $guna ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					</tr>
					<?php
					++$count;
endforeach;
				?>
			<?php endif; ?>
			<tr>
				<?php if ( 'advanced' === $result_type ) : ?>
					<th colspan="4" class="pk-astrology-text-center">Total Guna Milan Points :</th>
					<th><?php echo $result['gunaMilan']['maximumPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $result['gunaMilan']['totalPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<?php else : ?>
					<th colspan="4" class="pk-astrology-text-center">Total Guna Milan Points :
						<?php echo $result['gunaMilan']['totalPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?> / <?php echo $result['gunaMilan']['maximumPoints']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<?php endif; ?>
			</tr>
		</table>
		<?php if ( 'advanced' === $result_type ) : ?>
			<h3>Guna Milan Detailed Interpretation</h3>
			<?php $count = 1; foreach ( $result['gunaMilan']['guna'] as  $koot ) : ?>
				<span><?php echo $koot['id']; ?><b>. <?php echo $koot['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?> Koot</b></span>
				<p><?php echo $koot['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php
				++$count;
endforeach;
			?>

			<h3>Girl Mangal Dosha Details</h3>
			<p class="pk-astrology-alert <?php echo ( ( $result['girlMangalDoshaDetails']['hasMangalDosha'] ) ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success' ); ?>">
				<?php echo $result['girlMangalDoshaDetails']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</p>

			<h3>Boy Mangal Dosha Details</h3>
			<p class="pk-astrology-alert <?php echo ( ( $result['boyMangalDoshaDetails']['hasMangalDosha'] ) ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success' ); ?>">
				<?php echo $result['boyMangalDoshaDetails']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</p>
		<?php endif; ?>

		<div class="pk-astrology-alert pk-astrology-text-center pk-astrology-pad <?php echo ( ( 'bad' === $result['message']['type'] ) ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success' ); ?>">
			<?php echo $result['message']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	<?php endif; ?>
</div>
