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
<div class="main-content">

	<div class="container prokerala-api--container">
		<?php if ( null !== $chart ) : ?>

			<h3 class="pk-astrology-text-center">Synastry Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 700 700" ', $chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

		<?php endif; ?>

		<?php if ( null !== $aspect_chart ) : ?>

			<h3 class="pk-astrology-text-center">Synastry Aspect Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 500 500" ', $aspect_chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

		<?php endif; ?>

		<?php if ( null !== $planet_aspects ) : ?>

			<h3 class="pk-astrology-text-center mt-5">List of Aspects</h3>

			<table class="pk-astrology-table pk-astrology-table-responsive-sm">
				<tr>
					<th>Major Aspects</th>
					<td>Opposition, Conjunction, Sextile, Square, Trine</td>
				</tr>
				<tr>
					<th>Minor Aspects</th>
					<td>Semi Sextile, Semi Square, BiQuintile, Quincunx, Sesquiquadrate</td>
				</tr>
			</table>
			<!--            House table-->
			<h3 class="pk-astrology-text-center mt-5">Synastry Planet Aspects</h3>
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>Primary Planet</th>
						<th>Aspect</th>
						<th>Secondary Planet</th>
						<th>Orb</th>
					</tr>
					<?php foreach ( $planet_aspects->getAspects() as $aspect ) : ?>
						<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
							<tr>
								<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php foreach ( $planet_aspects->getAspects() as $aspect ) : ?>
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
	</div>
</div>
