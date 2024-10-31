<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Echo output directly to the console.
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\EchoConsole::init ());
 *     
 *     Analog::log ('Log me');
 *
 * Note: Uses Analog::$format for the output format.
 */
class EchoConsole {
	public static function init () {
		return function ($info) {
			vprintf (\SedoxWPCatalogue\Dependencies\Analog\Analog::$format, $info);
		};
	}
}
