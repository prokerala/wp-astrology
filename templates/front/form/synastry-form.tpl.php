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
<div class="row">
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-col-form-label pk-astrology-text-black pk-astrology-py-4 pk-astrology-text-xlarge">Enter Primary Profile</legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label">Date Of Birth:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<input type='datetime-local' name="partner_a_dob" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?= $primaryBirthTime->format('Y-m-d\TH:i')?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group row pk-astrology-text-small">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label  pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div class="pk-astrology-form-check pk-astrology-form-check-inline">
					<input class="pk-astrology-form-check-input birth_time_unknown" type="checkbox" name="partner_a_birth_time_unknown" id="partner_a_birth_time_unknown" <?=isset($primaryBirthTimeUnknown) && $primaryBirthTimeUnknown ? 'checked' : ''?>>
					<label class="pk-astrology-form-check-label" for="partner_a_birth_time_unknown">Exact primary birth time is unknown</label>
				</div>
			</div>
		</div>
		<div id="primaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label">Place of birth:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div id='a-location'>
					<input type='text' id="fin-partner-a-location" name="partner_a_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="partner_a" placeholder="Place of birth" value="" required="required"/>
				</div>
			</div>
		</div>
	</div>
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-col-form-label pk-astrology-text-black pk-astrology-py-4 pk-astrology-text-xlarge">Enter Secondary Profile</legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label">Date Of Birth:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<input type='datetime-local' name="partner_b_dob" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?= $secondaryBirthTime->format('Y-m-d\TH:i')?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row pk-astrology-text-small">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label  pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div class="pk-astrology-form-check pk-astrology-form-check-inline">
					<input class="pk-astrology-form-check-input birth_time_unknown" type="checkbox" name="partner_b_birth_time_unknown" id="partner_b_birth_time_unknown" <?=isset($secondaryBirthTimeUnknown) && $secondaryBirthTimeUnknown ? 'checked' : ''?>>
					<label class="pk-astrology-form-check-label" for="partner_b_birth_time_unknown">Exact secondary birth time is unknown</label>
				</div>
			</div>
		</div>
		<div id="secondaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label">Place of birth:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div id='b-location'>
					<input type='text' id="fin-partner-b-location" name="partner_b_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="partner_b" placeholder="Place of birth" value="" required="required"/>
				</div>
			</div>
		</div>
	</div>
</div>

<div>
	<?php if(isset($composite) && $composite) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Transit Date:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<input type='date' name="transit_datetime" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?= $transitDateTime->format('Y-m-d')?>"/>
			</div>
		</div>
		<div id="secondaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Reference Place:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<div id='b-location'>
					<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="current_" placeholder="Reference Place" value="" required="required"/>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(isset($synastry) && $synastry) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Synastry Chart Type: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<select name="chart_type">
					<option value="zodiac-contact-chart" <?= 'zodiac-contact-chart' === $chartType ? 'selected' : ''?>>Zodiacal Contact Chart</option>
					<option value="house-contact-chart" <?= 'house-contact-chart' === $chartType ? 'selected' : ''?>>House Contact Chart</option>
				</select>
			</div>
		</div>
	<?php endif; ?>

	<div class="pk-astrology-form-group pk-astrology-row">
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

	<?php if (null !== $aspectFilter): ?>
		<div class="pk-astrology-form-group pk-astrology-row">
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


	<div class="pk-astrology-form-group pk-astrology-row">
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

	<div class="pk-astrology-form-group pk-astrology-row d-none" id="birth_time_rectification_tab">
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
		const birthTimeUnknownCheckboxes = document.querySelectorAll('.birth_time_unknown');
		const birthTimeRectificationTab = document.getElementById('birth_time_rectification_tab');

		birthTimeUnknownCheckboxes.forEach(function (el) {
			el.addEventListener('click', () => {
				const $primaryCheckbox = document.getElementById('partner_a_birth_time_unknown');
				const $secondaryCheckbox = document.getElementById('partner_b_birth_time_unknown');
				if($primaryCheckbox.checked || $secondaryCheckbox.checked){
					birthTimeRectificationTab.classList.remove('d-none');
				} else {
					if(!birthTimeRectificationTab.classList.contains('d-none')){
						birthTimeRectificationTab.classList.add('d-none');
					}
				}
			});
		});
	}());
</script>
