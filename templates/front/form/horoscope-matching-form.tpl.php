<?php
/**
 * Common form inputs for horoscope matching reports.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2020 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2020 Ennexa Technologies Private Limited
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

<?php if ( empty( $options['ayanamsa'] ) ) : ?>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label ">Ayanamsa</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
			<select name="ayanamsa" class="pk-astrology-form-control">
				<option value="1" <?php echo '1' === (string) $ayanamsa ? 'selected' : ''; ?>>Lahiri</option>
				<option value="3" <?php echo '3' === (string) $ayanamsa ? 'selected' : ''; ?>>Raman</option>
				<option value="5" <?php echo '5' === (string) $ayanamsa ? 'selected' : ''; ?>>KP</option>
			</select>
		</div>
	</div>
<?php else : ?>
	<input type="hidden" name="ayanamsa" value="<?php echo (int) $options['ayanamsa']; ?>">
<?php endif; ?>
<div class="pk-astrology-row">
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-form-label pk-astrology-pad-xsmall">Enter Girl's Birth Details</legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-form-label">Date Of Birth:</label>
			<div class="pk-astrology-col-md-8">
				<input type='datetime-local' name="girl_dob" class="pk-astrology-form-control"  required="required" value="<?php echo $girl_dob->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>"/>
			</div>
		</div>
		<div id="glocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-form-label">Place of birth:</label>
			<div class="pk-astrology-col-md-8">
				<div id='g-location'>
					<input type='text' id="g-autocomplete" name="girl_location" autocomplete="off" class="pk-astrology-form-control prokerala-location-input" placeholder="Place of birth" value="" required="required"/>
				</div>
			</div>
		</div>
	</div>
	<div class="pk-astrology-col-12 pk-astrology-col-md-6">
		<legend class="pk-astrology-form-label pk-astrology-pad-xsmall">Enter Boy's Birth Details</legend>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-col-form-label">Date Of Birth:</label>
			<div class="pk-astrology-col-md-8">
				<input type='datetime-local' name="boy_dob" class="pk-astrology-form-control"  required="required" value="<?php echo $boy_dob->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>"/>
			</div>
		</div>
		<div id="blocationField" class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-md-4 pk-astrology-form-label">Place of birth:</label>
			<div class="pk-astrology-col-md-8">
				<div id='b-location'>
					<input type='text' id="b-coordinates" name="boy_location" autocomplete="off" class="pk-astrology-form-control prokerala-location-input" placeholder="Place of birth" required="required"/>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="form-hidden-fields"></div>
