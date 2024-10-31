<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

require_once __DIR__ . '/../../ChromePhp.php';

/**
 * Log to the [Chrome Logger](http://craig.is/writing/chrome-logger).
 * Based on the [ChromePhp library](https://github.com/ccampbell/chromephp).
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\ChromeLogger::init ());
 *     
 *     // send a debug message
 *     Analog::debug ($an_object);
 *
 *     // send an ordinary message
 *     Analog::info ('An error message');
 */
class ChromeLogger {
	public static function init () {
		return function ($info) {
			switch ($info['level']) {
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::DEBUG:
					\ChromePhp::log ($info['message']);
					break;
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::INFO:
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::NOTICE:
					\ChromePhp::info ($info['message']);
					break;
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::WARNING:
					\ChromePhp::warn ($info['message']);
					break;
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::ERROR:
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::CRITICAL:
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::ALERT:
				case \SedoxWPCatalogue\Dependencies\Analog\Analog::URGENT:
					\ChromePhp::error ($info['message']);
					break;
			}
		};
	}
}