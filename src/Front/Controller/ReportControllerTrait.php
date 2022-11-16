<?php
/**
 * Re-usable methods for report controllers.
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

namespace Prokerala\WP\Astrology\Front\Controller;

use Prokerala\Api\Astrology\Location;
use Prokerala\Astrology\Vendor\Buzz\Browser;
use Prokerala\Astrology\Vendor\Buzz\Client\Curl;
use Prokerala\Astrology\Vendor\Buzz\Client\FileGetContents;
use Prokerala\Astrology\Vendor\Slim\Psr7\Factory\RequestFactory;
use Prokerala\Astrology\Vendor\Slim\Psr7\Factory\ResponseFactory;
use Prokerala\Astrology\Vendor\Slim\Psr7\Factory\StreamFactory;
use Prokerala\Common\Api\Authentication\Oauth2;
use Prokerala\Common\Api\Client;
use Prokerala\WP\Astrology\Templating\Engine;

/**
 * Reusable bits for report controllers.
 *
 * @since   1.0.0
 */
// phpcs:disable WordPress.Security.NonceVerification.Missing
trait ReportControllerTrait {

	/**
	 * Template Engine.
	 *
	 * @since 1.0.0
	 *
	 * @var Engine
	 */
	private $view;

	/**
	 * Plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,string>
	 */
	private $options;

	/**
	 * Get template engine.
	 *
	 * @since 1.0.0
	 *
	 * @return Engine
	 */
	private function view() {
		if ( ! isset( $view ) ) {
			$this->view = new Engine();
		}

		return $this->view;
	}

	/**
	 * Set plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	private function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * Get plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	private function get_options() {
		return $this->options;
	}

	/**
	 * Get configured default timezone.
	 *
	 * @since 1.0.0
	 *
	 * @return \DateTimeZone
	 */
	private function get_default_timezone() {
		$tz = date_default_timezone_get();
		if ( isset( $this->options['timezone'] ) ) {
			$tz = $this->options['timezone'];
		}

		return new \DateTimeZone( $tz );
	}

	/**
	 * Get ayanamsa from user input.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_input_ayanamsa() {
		return isset( $_POST['ayanamsa'] ) ? (int) $_POST['ayanamsa'] : '';
	}

	/**
	 * Get timezone.
	 *
	 * @since 1.0.0
	 *
	 * @param string $prefix Input field prefix.
	 * @return \DateTimeZone
	 */
	protected function get_timezone( $prefix = '' ) {
		if ( ! isset( $_POST[ "{$prefix}timezone" ] ) ) {
			return $this->get_default_timezone();
		}

		return new \DateTimeZone( sanitize_text_field( wp_unslash( $_POST[ "{$prefix}timezone" ] ) ) );
	}

	/**
	 * Create location object from user input.
	 *
	 * @since 1.0.0
	 *
	 * @param \DateTimeZone|null $tz Timezone.
	 * @param string             $prefix Input field prefix.
	 * @return Location
	 */
	protected function get_location( $tz = null, $prefix = '' ) {
		$coordinates = '0,0';
		if ( isset( $_POST[ "{$prefix}coordinates" ] ) ) {
			$coordinates = sanitize_text_field( wp_unslash( $_POST[ "{$prefix}coordinates" ] ) );
		}
		[$latitude, $longitude] = explode( ',', $coordinates );

		if ( is_null( $tz ) ) {
			$tz = $this->get_timezone();
		}

		return new Location( $latitude, $longitude, 0, $tz );
	}

	/**
	 * Create api client.
	 *
	 * @since 1.0.0
	 *
	 * @return Client
	 */
	protected function get_api_client() {
		$request_factory  = new RequestFactory();
		$response_factory = new ResponseFactory();
		$stream_factory   = new StreamFactory();

		$client      = function_exists( 'curl_init' ) ? new Curl( $response_factory ) : new FileGetContents( $response_factory );
		$http_client = new Browser( $client, $request_factory );

		$client_id     = $this->options['client_id'];
		$client_secret = $this->options['client_secret'];

		$auth_client = new Oauth2( $client_id, $client_secret, $http_client, $request_factory, $stream_factory );

		return new Client( $auth_client, $http_client, $request_factory );
	}

	/**
	 * Render template.
	 *
	 * @since 1.0.0
	 *
	 * @param Engine              $template Template.
	 * @param array<string,mixed> $data Additional data.
	 *
	 * @return mixed|string
	 */
	private function render( $template, $data = [] ) {
		$tpl_file = PK_ASTROLOGY_PLUGIN_PATH . "templates/front/{$template}.tpl.php";

		return $this->view()->render( $tpl_file, $data );
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.1.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults() {
		return [
			'form_action' => null,
			'report'      => '',
			'result_type' => '',
		];
	}

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function can_render_result() {
		return (
			! isset( $_SERVER['REQUEST_METHOD'] )
			|| 'POST' === wp_unslash( $_SERVER['REQUEST_METHOD'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);
	}

	/**
	 * Get sanitized input value from POST request body.
	 *
	 * @since 1.1.0
	 *
	 * @param string $var Input parameter name.
	 * @param string $default Default value.
	 *
	 * @return string
	 */
	private function get_post_input( $var, $default = '' ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST[ $var ] ) ) {
			return $default;
		}

		return sanitize_text_field( wp_unslash( (string) $_POST[ $var ] ) );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}
}
// phpcs:enable WordPress.Security.NonceVerification.Missing
