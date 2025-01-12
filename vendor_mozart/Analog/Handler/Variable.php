<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Append the log info to a variable passed in as a reference.
 *
 * Usage:
 *
 *     $my_log = '';
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Variable::init ($my_log));
 *     
 *     Analog::log ('Log me');
 *     echo $my_log;
 *
 * Note: Uses Analog::$format for the appending format.
 */
class Variable {
	public static function init (&$log) {
		return function ($info, $buffered = false) use (&$log) {
			$log .= ($buffered)
				? $info
				: vsprintf (\SedoxWPCatalogue\Dependencies\Analog\Analog::$format, $info);
		};
	}
}