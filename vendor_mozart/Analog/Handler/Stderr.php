<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Send the output to STDERR.
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Stderr::init ());
 *     
 *     Analog::log ('Log me');
 *
 * Note: Uses Analog::$format for the appending format.
 */
class Stderr {
	public static function init () {
		return function ($info, $buffered = false) {
			file_put_contents ('php://stderr', ($buffered)
				? $info
				: vsprintf (\SedoxWPCatalogue\Dependencies\Analog\Analog::$format, $info));
		};
	}
}