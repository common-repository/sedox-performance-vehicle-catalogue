<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


class SettingsLinks extends BaseController
{
	public function register() {
		add_filter("plugin_action_links_$this->pluginName", [$this, 'settingsLink']);
	}

	public function settingsLink($links) {
		$settingsLink = '<a href="admin.php?page='.SEDOX_VDB_MAIN_MENU_SLUG.'">Settings</a>';
		array_push($links, $settingsLink);
		return $links;
	}
}
