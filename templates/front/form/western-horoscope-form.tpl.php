<?php
/**
 * Common form inputs for horoscope reports.
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

// phpcs:disable VariableAnalysis

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div>
	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Birth Date: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='datetime-local' name="datetime" class="pk-astrology-form-control pk-astrology-form-control-lg" required="required" value="<?= $datetime->format('Y-m-d\TH:i')?>"/>
		</div>
	</div>

	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="checkbox" name="birth_time_unknown" id="birth_time_unknown" <?= isset($birthTimeUnknown) && $birthTimeUnknown ? 'checked' : ''?>>
				<label class="pk-astrology-form-check-label" for="birth_time_unknown">Check if exact birth time is unknown</label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Birth Place:</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='text' id="fin-location" name="location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Place of birth" value="" required>
		</div>
	</div>

	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="house-system">House System: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="house_system" id="house-system">
				<option value="placidus" <?= 'placidus' === $houseSystem ? 'selected' : ''?>>Placidus</option>
				<option value="koch" <?= 'koch' === $houseSystem ? 'selected' : ''?>>Koch</option>
				<option value="whole-sign" <?= 'whole-sign' === $houseSystem ? 'selected' : ''?>>Whole Sign</option>
				<option value="equal-house" <?= 'equal-house' === $houseSystem ? 'selected' : ''?>>Equal House</option>
				<option value="m-house" <?= 'm-house' === $houseSystem ? 'selected' : ''?>>M House</option>
				<option value="porphyrius" <?= 'porphyrius' === $houseSystem ? 'selected' : ''?>>Porphyrius</option>
				<option value="regiomontanus" <?= 'regiomontanus' === $houseSystem ? 'selected' : ''?>>Regiomontanus</option>
				<option value="campanus" <?= 'campanus' === $houseSystem ? 'selected' : ''?>>Campanus</option>
			</select>
		</div>
	</div>
	<?php if (isset($transit) && $transit): ?>
		<div class="pk-astrology-form-group row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Transit Date Time: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='datetime-local' name="transit_datetime" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" required="required" value="<?= $transitDatetime->format('Y-m-d\TH:i')?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Transit Location:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current" required>
			</div>
		</div>
	<?php elseif (isset($progression) && $progression): ?>
		<div class="form-group row">
			<label class="col-sm-3 col-md-4 col-form-label text-md-right text-xs-left">Progression Year: </label>
			<div class="col-sm-9 col-md-6 ">
				<input type="number" name="progression_year" class="form-control form-control-lg rounded-1" placeholder="Enter Progression Year" value="<?= $progressionYear?>" required>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-md-4 col-form-label text-md-right text-xs-left ">Progressed Location:</label>
			<div class="col-sm-9 col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="form-control form-control-lg rounded-1 prokerala-location-input" placeholder="Progressed Location" data-location_input_prefix="current_" value="" required>
			</div>
		</div>
	<?php elseif (isset($solarReturn) && $solarReturn): ?>
		<div class="form-group row">
			<label class="col-sm-3 col-md-4 col-form-label text-md-right text-xs-left">Solar Return Year: </label>
			<div class="col-sm-9 col-md-6 ">
				<input type="number" name="solar_return_year" class="form-control form-control-lg rounded-1" placeholder="Enter Solar Return Year" value="<?= $solarYear?>" required>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-md-4 col-form-label text-md-right text-xs-left ">Transit Location:</label>
			<div class="col-sm-9 col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="form-control form-control-lg rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current_" required>
			</div>
		</div>
	<?php endif; ?>

	<?php if (false === $aspectFilter): ?>
	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Aspect Filter: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="aspect_filter" id="aspect-filter">
				<option value="major" <?= 'major' === $aspectFilter ? 'selected' : ''?>>Show major aspects</option>
				<option value="all" <?= 'all' === $aspectFilter ? 'selected' : ''?>>Show all aspects</option>
				<option value="minor" <?= 'minor' === $aspectFilter ? 'selected' : ''?>>Show minor aspects only</option>
			</select>
		</div>
	</div>
	<?php endif; ?>


	<div class="pk-astrology-form-group row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Orb: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb1" value="default" <?='default' === $orb ? 'checked' : ''?>>
				<label class="pk-astrology-form-check-label" for="orb1">Default</label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb2" value="exact" <?='exact' === $orb ? 'checked' : ''?>>
				<label class="pk-astrology-form-check-label" for="orb2">Exact</label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group row d-none" id="birth_time_rectification_tab">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Birth Time Rectification Chart: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification1" value="flat-chart" <?='flat-chart' === $rectificationChart ? 'checked' : ''?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification1">Flat Chart</label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification2" value="true-sunrise-chart" <?='true-sunrise-chart' === $rectificationChart ? 'checked' : ''?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification2">True Sunrise Chart</label>
			</div>
		</div>
	</div>
</div>

<div id="form-hidden-fields"></div>


<script>
	(function () {
		const $birthTimeUnknownCheckbox = document.getElementById('birth_time_unknown');
		const $birthTimeRectificationTab = document.getElementById('birth_time_rectification_tab');

		$birthTimeUnknownCheckbox.addEventListener('click', (e) => {
			if(e.target.checked){
				$birthTimeRectificationTab.classList.remove('d-none');
			} else {
				if(!$birthTimeRectificationTab.classList.contains('d-none')){
					$birthTimeRectificationTab.classList.add('d-none');
				}
			}
		});
	}());
</script>
