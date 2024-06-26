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


<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall">List of Aspects</h3>

<table class="pk-astrology-table pk-astrology-table-responsive-sm">
	<tr>
		<th><?php echo $translation_data['major_aspects']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<td><?php echo $translation_data['major_aspects_info']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
	</tr>
	<tr>
		<th><?php echo $translation_data['minor_aspects']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<td><?php echo $translation_data['minor_aspects_info']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
	</tr>
</table>

<!--            Aspect table-->
<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall"><?php echo $translation_data['planet_aspects']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
<table class="pk-astrology-table pk-astrology-table-responsive-sm">
	<tr>
		<th><?php echo $translation_data['planet_1']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['aspect']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['planet_2']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		<th><?php echo $translation_data['orb']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
	</tr>
	<?php foreach ( $planet_aspects as $aspect ) : ?>
		<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
			<tr>
				<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php foreach ( $planet_aspects as $aspect ) : ?>
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

<?php if ( isset( $declinations ) ) : ?>
	<h3 class="pk-astrology-text-center pk-astrology-pad-xsmall"><?php echo $translation_data['declination_aspects']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
	<table class="pk-astrology-table pk-astrology-table-responsive-sm">
		<tr>
			<th><?php echo $translation_data['planet_1']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			<th><?php echo $translation_data['aspect']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			<th><?php echo $translation_data['planet_2']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			<th><?php echo $translation_data['orb']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
		</tr>
		<?php foreach ( $declinations as $declination ) : ?>
			<tr>
				<td><?php echo $declination->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo $declination->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo $declination->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				<td><?php echo round( $declination->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
