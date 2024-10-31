<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Ignores anything sent to it so you can disable logging.
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Ignore::init ());
 *     
 *     Analog::log ('Log me');
 */
class Ignore {
	public static function init () {
		return function ($info) {
			// do nothing
		};
	}
}