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

	<div class="container">
		<?php if ( null !== $chart ) : ?>

		<h3 class="pk-astrology-text-center">Natal Chart</h3>
		<div class="mb-5 d-flex justify-content-center">
			<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 600 600" ', $chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php endif; ?>

		<?php if ( null !== $aspect_chart ) : ?>
		<h3 class="pk-astrology-text-center">Natal Aspect Chart</h3>
		<div class="mb-5 d-flex justify-content-center">
			<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 710 470" ', $aspect_chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php endif; ?>

		<?php if ( null !== $result ) : ?>
			<?php if ( $planet_positions ) : ?>

				<h3 class="pk-astrology-text-center">Natal Planet Positions</h3>

				<!--   Planet Position table  -->
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>Planet</th>
						<th>Longitude</th>
						<th>Motion</th>
						<th>House</th>
						<th>Zodiac</th>
					</tr>
					<?php foreach ( $result->getPlanetPositions() as $planet_position ) : ?>
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

				<h3 class="pk-astrology-text-center mt-5">Angles</h3>

				<!--            Planet Position table-->
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>Angle</th>
						<th>Longitude</th>
						<th>Degree</th>
						<th>House</th>
						<th>Zodiac</th>
					</tr>
					<?php foreach ( $result->getAngles() as $planet_position ) : ?>
						<tr>
							<td><?php echo $planet_position->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $planet_position->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $planet_position->getDegree(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $planet_position->getHouseNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $planet_position->getZodiac()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</table>

				<!--            House table-->
				<h3 class="pk-astrology-text-center mt-5">House Cusps</h3>
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>House</th>
						<th>Start Cusp</th>
						<th>End Cusp</th>
					</tr>
					<?php foreach ( $result->getHouses() as $house ) : ?>
						<tr>
							<td><?php echo $house->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $house->getStartCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $house->getEndCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</table>

			<?php endif; ?>
			<?php if ( $planet_aspects ) : ?>

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

				<!--            Aspect table-->
				<h3 class="pk-astrology-text-center mt-5">Planet Aspects</h3>
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>Planet 1</th>
						<th>Aspect</th>
						<th>Planet 2</th>
						<th>Orb</th>
					</tr>
					<?php foreach ( $result->getAspects() as $aspect ) : ?>
						<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
							<tr>
								<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php foreach ( $result->getAspects() as $aspect ) : ?>
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

				<h3 class="pk-astrology-text-center mt-5">Declination Aspects</h3>

				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr>
						<th>Planet 1</th>
						<th>Aspect</th>
						<th>Planet 2</th>
						<th>Orb</th>
					</tr>
					<?php foreach ( $result->getDeclinations() as $aspect ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		<?php endif; ?>
	</div>
