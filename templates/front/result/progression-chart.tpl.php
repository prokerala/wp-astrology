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

			<h3 class="pk-astrology-text-center">Progression Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 700 700" ', $chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

		<?php endif; ?>

		<?php if ( null !== $aspect_chart ) : ?>

			<h3 class="pk-astrology-text-center">Progression Aspect Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?php echo str_replace( '<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 710 470" ', $aspect_chart ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

		<?php endif; ?>


		<?php if ( null !== $result ) : ?>

			<table class="table table-bordered mt-5 mb-5">
				<tr>
					<th>Progression Year</th>
					<td><?php echo $result->getProgressionYear(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				</tr>
				<tr>
					<th>Progression Date</th>
					<td><?php echo $result->getProgressionDate()->format( 'd M Y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
				</tr>
			</table>

			<h3 class="pk-astrology-text-center">Progression Planet Positions</h3>

<!--				   Planet Position table  -->
			<table class="table table-bordered">
				<tr>
					<th>Planet</th>
					<th>Longitude</th>
					<th>Motion</th>
					<th>House</th>
					<th>Zodiac</th>
				</tr>
				<?php foreach ( $result->getProgressionDetails()->getPlanetPositions() as $planet_position ) : ?>
					<tr>
						<td><?php echo $planet_position->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo round( $planet_position->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo round( $planet_position->getDegree(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo $planet_position->getHouseNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo $planet_position->getZodiac()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h3 class="pk-astrology-text-center mt-5">Retrograding Planets</h3>

			<table class="table table-bordered">
				<?php foreach ( $result->getProgressionDetails()->getPlanetPositions() as $planet_position ) : ?>
					<?php if ( $planet_position->isRetrograde() || ! in_array( $planet_position->getId(), [ 103, 104 ], true ) ) : ?>
						<tr>
							<td><?php echo $planet_position->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>

				<?php endforeach; ?>
			</table>

			<h3 class="pk-astrology-text-center mt-5">Angles</h3>

			<!--            Planet Position table-->
			<table class="table table-bordered">
				<tr>
					<th>Angle</th>
					<th>Longitude</th>
					<th>Degree</th>
					<th>House</th>
					<th>Zodiac</th>
				</tr>
				<?php foreach ( $result->getProgressionDetails()->getAngles() as $planet_position ) : ?>
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
			<table class="table table-bordered">
				<tr>
					<th>House</th>
					<th>Start Cusp</th>
					<th>End Cusp</th>
				</tr>
				<?php foreach ( $result->getProgressionDetails()->getHouses() as $house ) : ?>
					<tr>
						<td><?php echo $house->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo round( $house->getStartCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo round( $house->getEndCusp()->getLongitude(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h3 class="pk-astrology-text-center mt-5">List of Aspects</h3>

			<table class="table table-bordered">
				<tr>
					<th>Major Aspects</th>
					<td>Opposition, Conjunction, Sextile, Square, Trine</td>
				</tr>
				<tr>
					<th>Minor Aspects</th>
					<td>Semi Sextile, Semi Square, BiQuintile, Quincunx, Sesquiquadrate</td>
				</tr>
				<tr>
					<th>Declination Aspects</th>
					<td>Parallel, Contra Parallel</td>
				</tr>
			</table>
			<!--            Aspect table-->
			<h3 class="pk-astrology-text-center mt-5">Planet Aspects</h3>
			<table class="table table-bordered">
				<tr>
					<th>Planet 1</th>
					<th>Aspect</th>
					<th>Planet 2</th>
					<th>Orb</th>
				</tr>
				<tr><th class="pk-astrology-text-center" colspan="4">Major Aspects</th></tr>
				<?php foreach ( $result->getProgressionDetails()->getAspects() as $aspect ) : ?>
					<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				<tr><th class="pk-astrology-text-center" colspan="4">Minor Aspects</th></tr>
				<?php foreach ( $result->getProgressionDetails()->getAspects() as $aspect ) : ?>
					<?php if ( ! in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>

				<?php endforeach; ?>
				<tr><th class="pk-astrology-text-center" colspan="4">Declination Aspects</th></tr>
				<?php foreach ( $result->getProgressionDetails()->getDeclinations() as $aspect ) : ?>
					<tr>
						<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<!--            Transit - Natal Aspects table-->
			<h3 class="pk-astrology-text-center mt-5">Progression - Natal Aspects</h3>
			<table class="table table-bordered">
				<tr>
					<th>Planet 1</th>
					<th>Aspect</th>
					<th>Planet 2</th>
					<th>Orb</th>
				</tr>

				<tr><th class="pk-astrology-text-center" colspan="4">Major Aspects</th></tr>

				<?php foreach ( $result->getProgressionNatalAspect() as $aspect ) : ?>
					<?php if ( in_array( $aspect->getAspect()->getName(), [ 'Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine' ], true ) ) : ?>
						<tr>
							<td><?php echo $aspect->getPlanetOne()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getAspect()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo $aspect->getPlanetTwo()->getName(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo round( $aspect->getOrb(), 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>

				<tr><th class="pk-astrology-text-center" colspan="4">Minor Aspects</th></tr>

				<?php foreach ( $result->getProgressionNatalAspect() as $aspect ) : ?>
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
