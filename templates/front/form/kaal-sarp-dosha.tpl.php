<?php
/**
 * KaalSarpDosha input form template.
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

	<?php $this->render( __DIR__ . '/horoscope-form.tpl.php' ); ?>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn">Get Result</button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
