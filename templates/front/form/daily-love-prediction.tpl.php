<?php
/**
 * Chart input form template.
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
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Day</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8">
			<select name="day" class="pk-astrology-form-control">
				<option value="yesterday" <?php echo 'yesterday' === $day ? 'selected' : ''; ?>>Yesterday</option>
				<option value="today" <?php echo 'today' === $day ? 'selected' : ''; ?>>Today</option>
				<option value="tomorrow" <?php echo 'tomorrow' === $day ? 'selected' : ''; ?>>Tomorrow</option>
			</select>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Primary Partner</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8">
			<select name="sign_one" class="pk-astrology-form-control">
				<option value="">Choose Sign</option>
				<?php foreach ( $signs as $val ) : ?>
					<option value="<?php echo $val; ?>" <?php echo $val === $sign_one ? 'selected' : ''; ?>><?php echo ucwords( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Secondary Partner</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8">
			<select name="sign_two" class="pk-astrology-form-control">
				<option value="">Choose Sign</option>
				<?php foreach ( $signs as $val ) : ?>
					<option value="<?php echo $val; ?>" <?php echo $val === $sign_two ? 'selected' : ''; ?>><?php echo ucwords( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn">Get Predictions</button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
<?php echo $options['attribution'] ? '<div class="pk-astrology-text-right"><em>Powered by <a href="https://www.prokerala.com/">Prokerala.com</a></em></div>' : ''; ?>
