<?php
/**
 * Auspicious Period result.
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

<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall"><?php echo $translation_data['planet_positions']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>

<!--   Planet Position table  -->
<table class="pk-astrology-table pk-astrology-table-responsive-sm">
	<tr>
		<th><?php echo $translation_data['planet']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['longitude']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['motion']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['zodiac']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
	</tr>
	<?php foreach ( $planet_positions as $planet_position ) : ?>
		<tr>
			<td>
				<?php echo $planet_position->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $planet_position->isRetrograde() && ! in_array( $planet_position->getId(), [ 103, 104 ], true ) ) : ?>
					(R)
				<?php endif; ?>
			</td>
			<td><?php echo round( $planet_position->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo round( $planet_position->getDegree(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo $planet_position->getHouseNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo $planet_position->getZodiac()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
	<?php endforeach; ?>
</table>

<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall"><?php echo $translation_data['angles']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>

<!--            Planet Position table-->
<table class="pk-astrology-table pk-astrology-table-responsive-sm">
	<tr>
		<th><?php echo $translation_data['angle']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['longitude']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['degree']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['zodiac']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
	</tr>
	<?php foreach ( $angles as $angle ) : ?>
		<tr>
			<td><?php echo $angle->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo round( $angle->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo round( $angle->getDegree(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo $angle->getHouseNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo $angle->getZodiac()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
	<?php endforeach; ?>
</table>

<!--            House table-->
<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall"><?php echo $translation_data['house_cusps']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
<table class="pk-astrology-table pk-astrology-table-responsive-sm">
	<tr>
		<th><?php echo $translation_data['house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['start_cusp']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['end_cusp']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
	</tr>
	<?php foreach ( $houses as $house ) : ?>
		<tr>
			<td><?php echo $house->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo round( $house->getStartCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			<td><?php echo round( $house->getEndCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
	<?php endforeach; ?>
</table>
