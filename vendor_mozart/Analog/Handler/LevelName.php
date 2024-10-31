<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Translates log level codes to their names
 *
 *
 * Usage:
 *
 *     // The log level (3rd value) must be formatted as a string
 *     Analog::$format = "%s - %s - %s - %s\n";
 * 
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\LevelName::init (
 *         SedoxWPCatalogue\Dependencies\Analog\Handler\File::init ($file)
 *     ));
 */
class LevelName {
	/**
	 * Translation list for log levels.
	 */
	private static $log_levels = array (
		\SedoxWPCatalogue\Dependencies\Analog\Analog::DEBUG    => 'DEBUG',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::INFO     => 'INFO',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::NOTICE   => 'NOTICE',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::WARNING  => 'WARNING',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::ERROR    => 'ERROR',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::CRITICAL => 'CRITICAL',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::ALERT    => 'ALERT',
		\SedoxWPCatalogue\Dependencies\Analog\Analog::URGENT   => 'URGENT'
	);

	/**
	 * This contains the handler to send to
	 */
	public static $handler;

	public static function init ($handler) {
		self::$handler = $handler;

		return function ($info) {
			if (isset(self::$log_levels[$info['level']])) {
				$info['level'] = self::$log_levels[$info['level']];
			}
			$handler = LevelName::$handler;
			$handler ($info);
		};
	}

}