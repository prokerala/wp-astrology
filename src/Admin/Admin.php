<?php
/**
 * Admin class.
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

namespace Prokerala\WP\Astrology\Admin;

use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Plugin;

/**
 * Admin Class.
 *
 * @since   1.0.0
 */
class Admin {

	/**
	 * Plugin settings page url.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $settings_url;
	/**
	 * Plugin configuration object.
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;

	/**
	 * Admin constructor.
	 *
	 * @param Configuration $config Plugin configuration object.
	 */
	public function __construct( Configuration $config ) {
		$settings_url       = 'admin.php?page=' . Plugin::SLUG;
		$this->settings_url = admin_url( $settings_url );

		$this->config = $config;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_notices', [ $this, 'notices' ] );
		add_action( 'admin_init', [ $this, 'init' ] );

		add_filter( 'plugin_action_links', [ $this, 'plugin_action_links' ], 10, 2 );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
	}

	/**
	 * Plugin actions links hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $links Links array.
	 * @param string   $file Plugin file.
	 * @return mixed
	 */
	public function plugin_action_links( $links, $file ) {
		if ( is_network_admin() || PK_ASTROLOGY_PLUGIN_BASENAME !== $file ) {
			return $links;
		}

		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', $this->settings_url, __( 'Settings' ) );
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Plugin row meta hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $links Links array.
	 * @param string   $file Plugin file.
	 * @return mixed
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( PK_ASTROLOGY_PLUGIN_BASENAME === $file ) {
			if ( ! is_network_admin() ) {
				$links[] = sprintf( '<a href="%1$s">%2$s</a>', $this->settings_url, __( 'Settings' ) );
			}
			$links[] = '<a href="https://api.prokerala.com/faq" target="_blank">' . __( 'FAQ' ) . '</a>';
			$links[] = '<a href="https://api.prokerala.com/contact" target="_blank">' . __( 'Support' ) . '</a>';
		}

		return $links;
	}

	/**
	 * Register the styles for the admin area.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix Current admin page.
	 * @return void
	 */
	public function enqueue_styles( $hook_suffix ) {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'astrology-settings',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/css/admin/settings.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix Current admin page.
	 * @return void
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( false === strpos( $hook_suffix, Plugin::SLUG ) ) {
			return;
		}

		wp_enqueue_script(
			'astrology-settings',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/js/admin/settings.js',
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);
	}

	/**
	 * Add plugin page in WordPress menu.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function menu() {
		add_menu_page(
			esc_html__( 'Astrology Settings', 'astrology' ),
			esc_html__( 'Astrology', 'astrology' ),
			'manage_options',
			Plugin::SLUG,
			[
				$this,
				'page_options',
			]
		);
	}

	/**
	 * Admin notices hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function notices() {
		global $hook_suffix;
		if ( 'plugins.php' !== $hook_suffix ) {
			return;
		}

		foreach ( $this->config->get_notices() as $notice ) {
			echo sprintf(
				'<div class="notice notice-%s"><p style="font-size:1.15em;">☀️ %s</p></div>',
				esc_attr( $notice['type'] ),
				wp_kses(
					$notice['message'],
					[
						'a'      => [ 'href' => [] ],
						'strong' => [],
						'pre'    => [],
						'code'   => [],
					]
				)
			);
		}
	}

	/**
	 * Register plugin admin hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$settings = new SettingsPage( $this->config );
		$settings->register();
	}

	/**
	 * Plugin page callback.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function page_options() {
		$settings = new SettingsPage( $this->config );
		$settings->render_form();
	}

	/**
	 * Get settings page url.
	 *
	 * @return string
	 */
	public function get_settings_url() {
		return $this->settings_url;
	}
}
