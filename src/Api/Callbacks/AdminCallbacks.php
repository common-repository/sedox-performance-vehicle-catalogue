<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Api\Callbacks;


use SedoxVDb\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminSettings()
	{
		return require_once "$this->pluginPath/templates/settings.php";
	}
}
