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
		<?php if (null !== $chart): ?>

			<h3 class="text-center">Composite Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?= str_replace('<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 600 600" ', $chart); ?>
			</div>


		<?php endif; ?>

		<?php if (null !== $aspectChart): ?>

			<h3 class="text-center">Composite Aspect Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?= str_replace('<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 710 470" ', $aspectChart); ?>
			</div>

		<?php endif; ?>

		<?php if (null !== $result): ?>

			<!--            House table-->
			<h3 class="text-center m-5">Composite House Cusps</h3>
			<table class="table table-bordered">
				<tr>
					<th>House</th>
					<th>Start Cusp</th>
					<th>End Cusp</th>
				</tr>
				<?php foreach($result->getCompositeHouses() as $house): ?>
					<tr>
						<td><?=$house->getNumber()?></td>
						<td><?=round($house->getStartCusp()->getLongitude(), 2)?></td>
						<td><?=round($house->getEndCusp()->getLongitude(), 2)?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h3 class="text-center">Composite Planet Position</h3>
			<table class="table table-bordered m-5">
				<tr>
					<th>Planet</th>
					<th>Longitude</th>
					<th>Degree</th>
					<th>House</th>
					<th>Zodiac</th>
				</tr>
				<?php foreach($result->getCompositePlanetPositions() as $planetPosition): ?>
					<tr>
						<td><?=$planetPosition->getName()?></td>
						<td><?=round($planetPosition->getLongitude(), 2)?></td>
						<td><?=round($planetPosition->getDegree(), 2)?></td>
						<td><?=$planetPosition->getHouseNumber()?></td>
						<td><?=$planetPosition->getZodiac()->getName()?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h3 class="text-center">Composite Angles</h3>
			<table class="table table-bordered m-5">
				<tr>
					<th>Angles</th>
					<th>Longitude</th>
					<th>Degree</th>
					<th>House</th>
					<th>Zodiac</th>
				</tr>
				<?php foreach($result->getCompositeAngles() as $planetPosition): ?>
					<tr>
						<td><?=$planetPosition->getName()?></td>
						<td><?=round($planetPosition->getLongitude(), 2)?></td>
						<td><?=round($planetPosition->getDegree(), 2)?></td>
						<td><?=$planetPosition->getHouseNumber()?></td>
						<td><?=$planetPosition->getZodiac()->getName()?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h3 class="text-center m-5">Composite Planet Aspect</h3>
			<table class="table table-bordered">
				<tr>
					<th>Planet 1</th>
					<th>Aspect</th>
					<th>Planet 2</th>
					<th>Orb</th>
				</tr>

				<tr><th class="text-center" colspan="4">Major Aspects</th></tr>

				<?php foreach($result->getCompositeAspects() as $aspect): ?>
					<?php if(!in_array($aspect->getAspect()->getName(), ['Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine'])): ?>
						<?php continue; ?>
					<?php endif; ?>
					<tr>
						<td><?=$aspect->getPlanetOne()->getName()?></td>
						<td><?=$aspect->getAspect()->getName()?></td>
						<td><?=$aspect->getPlanetTwo()->getName()?></td>
						<td><?=round($aspect->getOrb(), 2)?></td>
					</tr>
				<?php endforeach; ?>

				<tr><th class="text-center" colspan="4">Minor Aspects</th></tr>

				<?php foreach($result->getCompositeAspects() as $aspect): ?>
					<?php if(in_array($aspect->getAspect()->getName(), ['Opposition', 'Conjunction', 'Sextile', 'Square', 'Trine'])): ?>
						<?php continue; ?>
					<?php endif; ?>
					<tr>
						<td><?=$aspect->getPlanetOne()->getName()?></td>
						<td><?=$aspect->getAspect()->getName()?></td>
						<td><?=$aspect->getPlanetTwo()->getName()?></td>
						<td><?=round($aspect->getOrb(), 2)?></td>
					</tr>
				<?php endforeach; ?>
			</table>

		<?php endif; ?>
	</div>
</div>
