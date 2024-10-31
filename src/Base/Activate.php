<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


class Activate
{
	public static function activate() {
		flush_rewrite_rules();

		// if settings exist no need to reinitialize them
		if(get_option(SEDOX_VDB_MAIN_MENU_SLUG.'_configuration')) {
			return;
		}

		$defaultConfiguration = [];
		$defaultCustomization = [];
		foreach (Config::$optionsStatic['configuration'] as $option) {
			$defaultConfiguration[$option['name']] = $option['default'];
		}
		foreach (Config::$optionsStatic['customization'] as $option) {
			$defaultCustomization[$option['name']] = $option['default'];
		}

		update_option(SEDOX_VDB_MAIN_MENU_SLUG.'_configuration', $defaultConfiguration);
		update_option(SEDOX_VDB_MAIN_MENU_SLUG.'_customization', $defaultCustomization);
	}
}
