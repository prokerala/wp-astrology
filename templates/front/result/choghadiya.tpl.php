<?php
/**
 * Choghadiya input form.
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
<div class="row">
	<?php foreach ( $result as $type => $choghadiya ) : ?>
		<div class="col-12 col-md-6">
			<table class="table table-bordered border-white table-hover table-responsive-sm" style="margin-right: 10px">
				<tr class="bg-secondary text-white">
					<th colspan="4" class="text-center"><?php echo $type ? 'Day' : 'Night'; ?> Choghadiya</th>
				</tr>
				<tr><th>Name</th><th>Type</th><th>Start</th><th>End</th></tr>

				<?php foreach ( $choghadiya as $data ) : ?>
					<tr class="<?php echo 'Good' === $data['type'] ? 'table-warning' : ( 'Inauspicious' === $data['type'] ? 'table-danger' : 'table-success' ); ?>">
						<td><?php echo $data['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?><br><i><?php echo $data['vela'] ? $data['vela'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></i></td>
						<td><?php echo $data['type']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['start']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['end']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	<?php endforeach; ?>
</div>
