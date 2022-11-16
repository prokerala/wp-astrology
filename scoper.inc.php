<?php
/**
 * PHP-Scoper Configuration
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

use Isolated\Symfony\Component\Finder\Finder;

return [
	'prefix'                  => 'Prokerala\\Astrology\\Vendor',
	'finders'                 => [
		Finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.(json|lock)/' )
			->exclude(
				[
					'doc',
					'test',
					'test_old',
					'tests',
					'Tests',
					'vendor-bin',
				]
			)
			->in(
				[
					'vendor/fig/http-message-util',
					'vendor/kriswallsmith/buzz',
					'vendor/php-http/httplug',
					'vendor/php-http/promise',
					'vendor/prokerala/astrology-sdk',
					'vendor/psr',
					'vendor/ralouphie/getallheaders',
					'vendor/slim/psr7',
					'vendor/symfony/cache-contracts',
					'vendor/symfony/cache',
					'vendor/symfony/deprecation-contracts',
					'vendor/symfony/options-resolver',
					'vendor/symfony/polyfill-php73',
					'vendor/symfony/polyfill-php80',
					'vendor/symfony/service-contracts',
					'vendor/symfony/var-exporter',
				]
			)
			->name( [ '*.php' ] ),
		Finder::create()->append( [ 'composer.json' ] ),
	],

	'exclude-files'           => [
		'vendor/ralouphie/getallheaders/src/getallheaders.php',
	],

	'patchers'                => [],

	'whitelist'               => [],
	'exclude-namespaces'      => [
		'Prokerala',
		'/^Psr\\\/',
	],
	'expose-global-constants' => false,
	'expose-global-classes'   => false,
	'expose-global-functions' => false,
];
