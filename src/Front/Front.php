<?php
/**
 * Plugin front end class.
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

namespace Prokerala\WP\Astrology\Front;

use Prokerala\Common\Api\Exception\AuthenticationException;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Exception\ValidationException;
use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Plugin;

/**
 * Front end class.
 *
 * @since   1.0.0
 */
final class Front {
	/**
	 * Plugin configuration object
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;

	/**
	 * Front constructor.
	 *
	 * @param Configuration $config Configuration object.
	 */
	public function __construct( Configuration $config ) {
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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_shortcode( 'astrology', [ $this, 'render' ] );
		add_shortcode( 'astrology-form', [ $this, 'render_form' ] );
		add_shortcode( 'astrology-result', [ $this, 'render_result' ] );
	}

	/**
	 * Render short code
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return string
	 */
	public function render( $atts = [] ) {
		return $this->render_result( $atts ) . $this->render_form( $atts );
	}

	/**
	 * Replace shortcode with form HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_form( $atts = [] ) {
		static $args = [
			'report' => '',
		];

		$args = shortcode_atts( $args, $atts );

		try {
			$controller = $this->get_controller( $args['report'] );

			return $controller->render_form();
		} catch ( \RuntimeException $e ) {
			return '<blockquote>Invalid report type</blockquote>';
		}
	}

	/**
	 * Check whether current request is via POST.
	 *
	 * @return bool
	 */
	private function is_post_request() {
		return (
			! isset( $_SERVER['REQUEST_METHOD'] )
			|| 'POST' === wp_unslash( $_SERVER['REQUEST_METHOD'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);
	}

	/**
	 * Replace shortcode with result.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_result( $atts = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		static $args = [
			'report' => '',
		];

		try {
			if ( ! $this->is_post_request() ) {
				return '';
			}

			$args = shortcode_atts( $args, $atts );

			$controller = $this->get_controller( $args['report'] );

			return $controller->process();
		} catch ( ValidationException $e ) {
			$errors     = $e->getValidationErrors();
			$error_code = '<ul>';
			foreach ( $errors as $error ) {
				$error_code .= '<li>' . $error->detail . '</li>';
			}
			$error_code .= '</ul>';
			return $error_code;
		} catch ( QuotaExceededException $e ) {
			return '<blockquote><p>You have exceeded your quota allocation</p></blockquote>';
		} catch ( RateLimitExceededException $e ) {
			return 'Rate limit exceeded. Throttle your requests.';
		} catch ( AuthenticationException $e ) {
			return '<blockquote><p>' . wp_kses( $e->getMessage(), [] ) . '</p></blockquote>';
		} catch ( \Exception $e ) {
			return '<blockquote><p>Request failed with error <em>' . wp_kses( $e->getMessage(), [] ) . '</em></p></blockquote>';
		}
	}

	/**
	 * Enqueue styles for the front area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! $this->is_shortcode_enabled() ) {
			return;
		}

		wp_enqueue_style(
			'pk-astrology',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/css/main.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Enqueue scripts for the front area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_shortcode_enabled() ) {
			return;
		}

		$options = $this->config->get_options();

		wp_enqueue_script(
			'pk-astrology-location-widget',
			'https://client-api.prokerala.com/static/js/location.min.js',
			[],
			Plugin::VERSION,
			true
		);
		wp_enqueue_script(
			'pk-astrology',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/js/main.js',
			[ 'pk-astrology-location-widget' ],
			Plugin::VERSION,
			true
		);
		wp_add_inline_script(
			'pk-astrology',
			'window.CLIENT_ID = ' . wp_json_encode( $options['client_id'] ),
			'before'
		);
	}

	/**
	 * Get controller from report name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $report Report type.
	 * @return ReportControllerInterface
	 * @throws \RuntimeException Throws on invalid report type.
	 */
	private function get_controller( $report ) {
		static $controller = null;

		if ( is_null( $controller ) ) {
			$report_controller = ucwords( $report ) . 'Controller';
			$controller_class  = "Prokerala\\WP\\Astrology\\Front\\Report\\{$report_controller}";

			if ( ! class_exists( $controller_class ) ) {
				throw new \RuntimeException( 'Invalid report type' );
			}
			$controller = new $controller_class( $this->config->get_options() );
		}

		return $controller;
	}

	/**
	 * Check whether short code is enabled in current page/post.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_shortcode_enabled() {
		global $post;

		return is_a( $post, 'WP_Post' ) || has_shortcode( $post->post_content, 'astrology' );
	}
}
