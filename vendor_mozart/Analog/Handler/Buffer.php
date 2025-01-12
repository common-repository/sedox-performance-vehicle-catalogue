<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Buffers messages to be sent as a batch to another handler at the end
 * of the request. Currently only works with the Mail handler.
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Buffer::init (
 *         SedoxWPCatalogue\Dependencies\Analog\Handler\Mail::init ($to, $subject, $from)
 *     ));
 *     
 *     // will all be buffered into one email
 *     Analog::log ('Message one', Analog::DEBUG);
 *     Analog::log ('Message two', Analog::WARNING);
 *     Analog::log ('Message three', Analog::ERROR);
 *
 * Note: Uses Analog::$format to format the messages as they're appended
 * to the buffer.
 */
class Buffer {
	/**
	 * This builds a log string of all messages logged.
	 */
	public static $buffer = '';

	/**
	 * This contains the handler to send to on close.
	 */
	private static $handler;

	/**
	 * A copy of our destructor object that will call close() on our behalf,
	 * since static classes can't have their own __destruct() methods.
	 */
	private static $destructor;

	/**
	 * Accepts another handler function to be used on close().
	 */
	public static function init ($handler) {
		self::$handler = $handler;
		self::$destructor = new \SedoxWPCatalogue\Dependencies\Analog\Handler\Buffer\Destructor ();

		return function ($info) {
			Buffer::$buffer .= vsprintf (\SedoxWPCatalogue\Dependencies\Analog\Analog::$format, $info);
		};
	}

	/**
	 * Passes the buffered log to the final $handler.
	 */
	public static function close () {
		$handler = self::$handler;
		return $handler (self::$buffer, true);
	}
}
