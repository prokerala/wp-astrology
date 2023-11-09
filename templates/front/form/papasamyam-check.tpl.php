<?php
/**
 * PapasamyamCheck input form template.
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

	<?php $this->render( __DIR__ . '/horoscope-matching-form.tpl.php' ); ?>

	<?php if ($enable_lang): ?>
		<?php $selected_lang ??= 'en'; ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label" for="select-lang"><?= $translation_data['location']?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
				<select name="lang" id="select-lang">
					<option value='en' <?= 'en' ===  $selected_lang ? 'selected' : ''; ?>><?= $translation_data['en']?></option>
					<option value='ml' <?= 'ml' ===  $selected_lang ? 'selected' : ''; ?>><?= $translation_data['ml']?></option>
					<option value='ta' <?= 'ta' ===  $selected_lang ? 'selected' : ''; ?>><?= $translation_data['ta']?></option>
					<option value='hi' <?= 'hi' ===  $selected_lang ? 'selected' : ''; ?>><?= $translation_data['hi']?></option>
				</select>
			</div>
		</div>

	<?php endif;?>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn"><?= $translation_data['get_result']?></button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
<?php echo $options['attribution'] ? '<div class="pk-astrology-text-right"><em>Powered by <a href="https://www.prokerala.com/">Prokerala.com</a></em></div>' : ''; ?>
