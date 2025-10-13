<?php
/**
 * Dasha Period result.
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
?>

<div class="pk-astrology-theme-<?php echo esc_attr( $options['theme'] ); ?>">
	<?php if ( ! empty( $result ) ) : ?>
			<?php if ( isset( $result['dasha_periods'] ) ) : ?>
				<div class="pk-astrology-dasha-periods">
					<?php foreach ( $result['dasha_periods'] as $mahadashas ) : ?>
						<h3><?php echo $translation_data['anthardashas_in']; // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo $mahadashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo $translation_data['mahadasha']; // phpcs:ignore WordPress.Security.EscapeOutput ?> </h3>
						<div class="pk-astrology-row">
							<?php foreach ( $mahadashas['antardasha'] as $anthardashas ) : ?>
								<table class="pk-astrology-table pk-astrology-col-12 pk-astrology-table-responsive-sm">
									<tr>
										<th><?php echo $translation_data['ad']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
										<th><?php echo $translation_data['pd']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
										<th><?php echo $translation_data['start']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
										<th><?php echo $translation_data['end']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
									</tr>
									<?php foreach ( $anthardashas['pratyantardasha'] as $pratyantardashas ) : ?>
										<tr>
											<td><?php echo $anthardashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
											<td><?php echo $pratyantardashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
											<td><?php echo $pratyantardashas['start']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
											<td><?php echo $pratyantardashas['end']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
										</tr>
									<?php endforeach; ?>
								</table>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
					<p class="pk-astrology-text-small pk-astrology-text-right pk-astrology-text-danger"><span class="pk-astrology-text-danger">**</span> <?php echo $translation_data['anthardashas_and_pratyantar_dasha']; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				</div>
			<?php endif; ?>
	<?php endif; ?>
</div>

