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
$gender = $gender ?? 'male';
?>
<div class="pk-astrology-row">
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-col-form-label pk-astrology-text-black pk-astrology-py-4 pk-astrology-text-xlarge"><?php echo $translation_data['enter_primary_profile']; // phpcs:ignore WordPress.Security.EscapeOutput ?></legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label"><?php echo $translation_data['dob']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<input type='datetime-local' name="partner_a_dob" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?php echo $primary_birth_time->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row pk-astrology-text-small">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label  pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div class="pk-astrology-form-check pk-astrology-form-check-inline">
					<input class="pk-astrology-form-check-input birth_time_unknown" type="checkbox" name="partner_a_birth_time_unknown" id="partner_a_birth_time_unknown" <?php echo isset( $primary_birth_time_unknown ) && $primary_birth_time_unknown ? 'checked' : ''; ?>>
					<label class="pk-astrology-form-check-label" for="partner_a_birth_time_unknown"><?php echo $translation_data['partner_a_birthtime_unknown']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				</div>
			</div>
		</div>
		<div id="primaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label"><?php echo $translation_data['pob']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div id='a-location'>
					<input type='text' id="fin-partner-a-location" name="partner_a_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="partner_a" placeholder="Place of birth" value="" required="required"/>
				</div>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="PrimaryGender"><?php echo $translation_data['gender']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<select name="partner_a_gender" id="PrimaryGender">
					<option value="male" <?php echo 'male' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['male']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="female" <?php echo 'female' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['female']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="pnts" <?php echo 'pnts' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['other']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				</select>
			</div>
		</div>
	</div>
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-col-form-label pk-astrology-text-black pk-astrology-py-4 pk-astrology-text-xlarge"><?php echo $translation_data['enter_secondary_profile']; // phpcs:ignore WordPress.Security.EscapeOutput ?></legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label"><?php echo $translation_data['dob']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<input type='datetime-local' name="partner_b_dob" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?php echo $secondary_birth_time->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row pk-astrology-text-small">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label  pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div class="pk-astrology-form-check pk-astrology-form-check-inline">
					<input class="pk-astrology-form-check-input birth_time_unknown" type="checkbox" name="partner_b_birth_time_unknown" id="partner_b_birth_time_unknown" <?php echo isset( $secondary_birth_time_unknown ) && $secondary_birth_time_unknown ? 'checked' : ''; ?>>
					<label class="pk-astrology-form-check-label" for="partner_b_birth_time_unknown"><?php echo $translation_data['partner_b_birthtime_unknown']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				</div>
			</div>
		</div>
		<div id="secondaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-pr-md-0 pk-astrology-col-form-label"><?php echo $translation_data['pob']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-md-8 pk-astrology-pl-md-0">
				<div id='b-location'>
					<input type='text' id="fin-partner-b-location" name="partner_b_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="partner_b" placeholder="Place of birth" value="" required="required"/>
				</div>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="SecondaryGender"><?php echo $translation_data['gender']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<select name="partner_b_gender" id="SecondaryGender">
					<option value="male" <?php echo 'male' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['male']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="female" <?php echo 'female' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['female']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="pnts" <?php echo 'pnts' === $gender ? 'selected' : ''; ?>><?php echo $translation_data['other']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				</select>
			</div>
		</div>
	</div>
</div>


<div>
	<?php if ( isset( $transit_datetime ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['transit_date_time']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<input type='date' name="transit_datetime" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1"  required="required" value="<?php echo $transit_datetime->format( 'Y-m-d' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			</div>
		</div>
		<div id="secondaryLocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['reference_place']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<div id='b-location'>
					<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="porutham-form-input autocomplete pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" data-location_input_prefix="current_" placeholder="Reference Place" value="" required="required"/>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $chart_type ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['Synastry_chart_type']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
				<select name="chart_type">
					<option value="zodiac-contact-chart" <?php echo 'zodiac-contact-chart' === $chart_type ? 'selected' : ''; ?>><?php echo $translation_data['zodiacal_contract_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="house-contact-chart" <?php echo 'house-contact-chart' === $chart_type ? 'selected' : ''; ?>><?php echo $translation_data['house_contract_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				</select>
			</div>
		</div>
	<?php endif; ?>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="house-system"><?php echo $translation_data['house_system']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="house_system" id="house-system">
				<option value="placidus" <?php echo 'placidus' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['placidus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="koch" <?php echo 'koch' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['koch']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="whole-sign" <?php echo 'whole-sign' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['whole_sign']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="equal-house" <?php echo 'equal-house' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['equal_house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="m-house" <?php echo 'm-house' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['m_house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="porphyrius" <?php echo 'porphyrius' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['porphyrius']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="regiomontanus" <?php echo 'regiomontanus' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['regiomontanus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="campanus" <?php echo 'campanus' === $house_system ? 'selected' : ''; ?>><?php echo $translation_data['campanus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
			</select>
		</div>
	</div>

	<?php if ( null !== $aspect_filter ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<select name="aspect_filter" id="aspect-filter">
					<option value="major" <?php echo 'major' === $aspect_filter ? 'selected' : ''; ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="all" <?php echo 'all' === $aspect_filter ? 'selected' : ''; ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
					<option value="minor" <?php echo 'minor' === $aspect_filter ? 'selected' : ''; ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				</select>
			</div>
		</div>
	<?php endif; ?>


	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['orb']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb1" value="default" <?php echo 'default' === $orb ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="orb1"><?php echo $translation_data['default']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb2" value="exact" <?php echo 'exact' === $orb ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="orb2"><?php echo $translation_data['exact']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row" id="birth_time_rectification_tab" hidden>
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['birth_time_rectification_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification1" value="flat-chart" <?php echo 'flat-chart' === $rectification_chart ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification1"><?php echo $translation_data['flat_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification2" value="true-sunrise-chart" <?php echo 'true-sunrise-chart' === $rectification_chart ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification2"><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
		</div>
	</div>
</div>
<div id="form-hidden-fields"></div>


<script>
	(function () {
		const $birthTimeUnknownCheckboxes = document.querySelectorAll('.birth_time_unknown');
		const $birthTimeRectificationTab = document.getElementById('birth_time_rectification_tab');

		$birthTimeUnknownCheckboxes.forEach(function (el) {
			if (el.checked) {
				$birthTimeRectificationTab.removeAttribute('hidden');
			}
			el.addEventListener('click', () => {
				const $primaryCheckbox = document.getElementById('partner_a_birth_time_unknown');
				const $secondaryCheckbox = document.getElementById('partner_b_birth_time_unknown');
				console.log($primaryCheckbox.checked, $secondaryCheckbox.checked);
				if($primaryCheckbox.checked || $secondaryCheckbox.checked){
					$birthTimeRectificationTab.removeAttribute('hidden');
				} else {
					$birthTimeRectificationTab.setAttribute('hidden', 'true');
				}
			});
		});
	}());
</script>
