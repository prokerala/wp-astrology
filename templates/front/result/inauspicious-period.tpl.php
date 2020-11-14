<?php
/**
 * Inauspicious Period result.
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
	<div class="pk-astrology-row">
		<h2 class="pk-astrology-text-center">Inauspicious Timing</h2>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm pk-astrology-text-center">
			<tr class="pk-astrology-bg-secondary"><th>Inauspicious Yogas</th><th>Time</th></tr>
			<?php foreach ( $result as $key => $data ) : ?>
				<tr>
					<td><?php echo $data['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<td>
						<?php foreach ( $data['period'] as $value ) : ?>
							<?php echo $value['start']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> to <?php echo $value['end']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <br>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>
<?php
