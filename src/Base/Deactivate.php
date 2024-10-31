<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Base;


class Deactivate {
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
