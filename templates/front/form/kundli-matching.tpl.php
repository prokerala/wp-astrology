<?php
/**
 * KundliMatching input form template.
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
<form class="pk-astrology-theme-<?php echo $options['theme']; ?> pk-astrology-form" method="POST">

	<?php $this->render( __DIR__ . '/horoscope-matching-form.tpl.php' ); ?>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Result Type: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="result_type" id="result_type1" value="basic" <?php echo 'basic' === $result_type ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="result_type1">Basic</label>
			</div>
			<div class="pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="result_type" id="result_type2" value="advanced" <?php echo 'advanced' === $result_type ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="result_type2">Advanced</label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn">Get Result</button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
<?php echo $options['attribution'] ? '<label class="pk-astrology-text-right"><em>Powered by Prokerala</em></label>' : ''; ?>
