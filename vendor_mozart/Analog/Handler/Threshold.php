<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Only writes log messages above a certain threshold
 *
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Threshold::init (
 *         SedoxWPCatalogue\Dependencies\Analog\Handler\File::init ($file),
 *         Analog::ERROR
 *     ));
 *     
 *     // Only message three will be logged
 *     Analog::log ('Message one', Analog::DEBUG);
 *     Analog::log ('Message two', Analog::WARNING);
 *     Analog::log ('Message three', Analog::URGENT);
 *
 * Note: Uses Analog::$format to format the messages as they're appended
 * to the buffer.
 */
class Threshold {
	/**
	 * This contains the handler to send to on close.
	 */
	public static $handler;

	/**
	 * Accepts another handler function to be used on close().
	 * $until_level defaults to ERROR.
	 */
	public static function init ($handler, $until_level = 3) {
		self::$handler = $handler;

		return function ($info) use ($until_level) {
			if ($info['level'] <= $until_level) {
				$handler = Threshold::$handler;
				$handler ($info);
			}
		};
	}

}