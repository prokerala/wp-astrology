<?php
/**
 * Birth Details input form template.
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

// phpcs:disable VariableAnalysis, WordPress.Security.EscapeOutput.OutputNotEscaped

// Exit if accessed directly.
use Prokerala\WP\Astrology\Templating\Context;

/**
 * Render Context.
 *
 * @var Context $this
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="pk-astrology-theme-<?php echo $options['theme']; ?> pk-astrology-form" method="POST" <?php echo isset( $options['form_action'] ) ? " action=\"{$options['form_action']}\"" : ''; ?>>
	<legend><?php echo $selected_calculator_name; ?></legend>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">First Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="first_name" class="pk-astrology-form-control" required="required" value="" placeholder="Enter first name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Middle Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="middle_name" class="pk-astrology-form-control" required="required" value="" placeholder="Enter middle name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Last Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="last_name" class="pk-astrology-form-control" required="required" value="" placeholder="Enter last name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Date of Birth: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='date' name="date" class="pk-astrology-form-control" required="required" value="<?php echo $datetime->format( 'Y-m-d' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Enter reference year: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='number' name="reference_year" class="pk-astrology-form-control" required="required" value="<?php echo $reference_year; ?>" placeholder="Enter reference year"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Consider additional vowel `yw` in calculation: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<label>
				<input type='checkbox' name="additional_vowel" value="1"/>
				Additional Vowel
			</label>
		</div>
	</div>
	<input type="hidden" name="system" value="<?php echo $selected_system; ?>">
	<input type="hidden" name="calculator" value="<?php echo $selected_calculator; ?>">

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn">Get Result</button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
<?php echo $options['attribution'] ? '<div class="pk-astrology-text-right"><em>Powered by <a href="https://www.prokerala.com/">Prokerala.com</a></em></div>' : ''; ?>
