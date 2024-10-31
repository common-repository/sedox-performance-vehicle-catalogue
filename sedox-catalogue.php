<?php

/**
 * @package SedoxPerformanceCatalogPlugin
 */

/*
Plugin Name: Sedox Performance Vehicle Catalogue
Plugin URI:
Description: Sedox Performance Vehicle Catalogue Wordpress plugin
Version: 1.5.1-build.1
Text Domain: sedox-catalogue
Domain Path: /languages
Author: Sedox Performance
Author URI: https://sedox-performance.com
Licence: GPLv2 or later
 */


/*
Copyright (C) 2019 Sedox Performance

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

use SedoxVDb\Base\Activate;
use SedoxVDb\Base\Deactivate;
use SedoxVDb\Base\Uninstall;
use SedoxVDb\Init;

defined('ABSPATH') or die('Nope');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// load guzzle functions because guzzle is stupid
include_once __DIR__.'/vendor_mozart/GuzzleHttp/functions.php';
include_once __DIR__.'/vendor_mozart/GuzzleHttp/Psr7/functions.php';
include_once __DIR__.'/vendor_mozart/GuzzleHttp/Promise/functions.php';

define('SEDOX_VDB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEDOX_VDB_PLUGIN_NAME', plugin_basename(__FILE__));
define('SEDOX_VDB_MAIN_MENU_SLUG', 'sedox_vehicle_catalogue');
define('SEDOX_VDB_TEXT_DOMAIN', 'sedox-catalogue');

function sedoxVDbActivatePlugin() {
	Activate::activate();
}

function sedoxVDbDeactivatePlugin() {
	Deactivate::deactivate();
}

function sedoxVDbUninstallPlugin() {
	Uninstall::uninstall();
}

register_activation_hook(__FILE__, 'sedoxVDbActivatePlugin');

register_deactivation_hook(__FILE__, 'sedoxVDbDeactivatePlugin');

register_uninstall_hook(__FILE__, 'sedoxVDbUninstallPlugin');

if (class_exists('SedoxVDb\\Init')) {
	$init = new Init();
	$init->registerServices();
	$init->registerShortcodeAndWidget();
}
