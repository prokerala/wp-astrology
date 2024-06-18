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
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['birth_date']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='datetime-local' name="datetime" class="pk-astrology-form-control pk-astrology-form-control-lg" required="required" value="<?php echo $datetime->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="checkbox" name="birth_time_unknown" id="birth_time_unknown" <?php echo isset( $birth_time_unknown ) && $birth_time_unknown ? 'checked' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_unknown"><?php echo $translation_data['time_unknown_message']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left "><?php echo $translation_data['birth_place']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='text' id="fin-location" name="location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Place of birth" value="" required>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="Gender"><?php echo $translation_data['gender']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="gender" id="Gender">
				<option value="male" <?php echo 'male' === $gender ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['male']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="female" <?php echo 'female' === $gender ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['female']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="pnts" <?php echo 'pnts' === $gender ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['other']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
			</select>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="house-system"><?php echo $translation_data['house_system']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="house_system" id="house-system">
				<option value="placidus" <?php echo 'placidus' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['placidus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="koch" <?php echo 'koch' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['koch']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="whole-sign" <?php echo 'whole-sign' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['whole_sign']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="equal-house" <?php echo 'equal-house' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['equal_house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="m-house" <?php echo 'm-house' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['m_house']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="porphyrius" <?php echo 'porphyrius' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['porphyrius']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="regiomontanus" <?php echo 'regiomontanus' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['regiomontanus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="campanus" <?php echo 'campanus' === $house_system ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['campanus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
			</select>
		</div>
	</div>
	<?php if ( isset( $transit_datetime ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['transit_date_time']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='datetime-local' name="transit_datetime" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" required="required" value="<?php echo $transit_datetime->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left "><?php echo $translation_data['transit_location']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current" required>
			</div>
		</div>
	<?php elseif ( isset( $progression_year ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['progression_year']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type="number" name="progression_year" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" placeholder="Enter Progression Year" value="<?php echo $progression_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
			</div>
		</div>

		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left "><?php echo $translation_data['progression_location']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="col-sm-9 col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Progressed Location" data-location_input_prefix="current" value="" required>
			</div>
		</div>
	<?php elseif ( isset( $solar_return_year ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"><?php echo $translation_data['solar_return_year']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type="number" name="solar_return_year" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" placeholder="Enter Solar Return Year" value="<?php echo $solar_return_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
			</div>
		</div>

		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left "><?php echo $translation_data['transit_location']; // phpcs:ignore WordPress.Security.EscapeOutput ?>:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current_" required>
			</div>
		</div>
	<?php endif; // phpcs:ignore WordPress.Security.EscapeOutput ?>

	<?php if ( null !== $aspect_filter ) : ?>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="aspect_filter" id="aspect-filter">
				<option value="major" <?php echo 'major' === $aspect_filter ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="all" <?php echo 'all' === $aspect_filter ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
				<option value="minor" <?php echo 'minor' === $aspect_filter ? 'selected' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></option>
			</select>
		</div>
	</div>
	<?php endif; // phpcs:ignore WordPress.Security.EscapeOutput ?>


	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['orb']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb1" value="default" <?php echo 'default' === $orb ? 'checked' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<label class="pk-astrology-form-check-label" for="orb1"><?php echo $translation_data['default']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb2" value="exact" <?php echo 'exact' === $orb ? 'checked' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<label class="pk-astrology-form-check-label" for="orb2"><?php echo $translation_data['exact']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row" id="birth_time_rectification_tab" hidden>
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter"><?php echo $translation_data['birth_time_rectification_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?>: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification1" value="flat-chart" <?php echo 'flat-chart' === $rectification_chart ? 'checked' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification1"><?php echo $translation_data['flat_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification2" value="true-sunrise-chart" <?php echo 'true-sunrise-chart' === $rectification_chart ? 'checked' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification2"><?php echo $translation_data['true_sunrise_chart']; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
			</div>
		</div>
	</div>
</div>

<div id="form-hidden-fields"></div>


<script>
	(function () {
		const $birthTimeUnknownCheckbox = document.getElementById('birth_time_unknown');
		const $birthTimeRectificationTab = document.getElementById('birth_time_rectification_tab');
		if ($birthTimeUnknownCheckbox.checked) {
			$birthTimeRectificationTab.setAttribute('hidden', 'true');
		}
		$birthTimeUnknownCheckbox.addEventListener('click', (e) => {
			if(e.target.checked){
				$birthTimeRectificationTab.removeAttribute('hidden');
			} else {
				$birthTimeRectificationTab.setAttribute('hidden', 'true');
			}
		});
	}());
</script>
