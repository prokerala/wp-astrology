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

			<h3 class="text-center">Synastry Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?= str_replace('<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 700 700" ', $chart); ?>
			</div>

		<?php endif; ?>

		<?php if (null !== $aspectChart): ?>

			<h3 class="text-center">Synastry Aspect Chart</h3>
			<div id="chart" class="d-flex justify-content-center">
				<?= str_replace('<svg ', '<svg preserveAspectRatio="none" viewBox="0 0 500 500" ', $aspectChart); ?>
			</div>

		<?php endif; ?>

		<?php if (null !== $result): ?>

			<!--            House table-->
			<h3 class="text-center">Synastry Planet Aspect</h3>
				<table class="table table-bordered">
					<tr>
						<th>Primary Planet</th>
						<th>Aspect</th>
						<th>Secondary Planet</th>
						<th>Orb</th>
					</tr>
					<tr><th class="text-center" colspan="4">Major Aspects</th></tr>

					<?php foreach($result->getAspects() as $aspect): ?>
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

					<?php foreach($result->getAspects() as $aspect): ?>
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
