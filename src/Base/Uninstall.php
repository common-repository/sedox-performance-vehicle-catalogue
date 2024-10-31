<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


use SedoxVDb\Util\Util;

class Uninstall
{
	public static function uninstall() {
		flush_rewrite_rules();

		delete_option(SEDOX_VDB_MAIN_MENU_SLUG.'_configuration');
		delete_option(SEDOX_VDB_MAIN_MENU_SLUG.'_customization');
		Util::clearCache();
	}
}
