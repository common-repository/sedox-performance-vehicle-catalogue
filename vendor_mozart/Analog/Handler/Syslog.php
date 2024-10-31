<?php

namespace SedoxWPCatalogue\Dependencies\Analog\Handler;

/**
 * Send the log message to the syslog service. This was borrowed largely
 * from the Monolog syslog handler.
 *
 * Usage:
 *
 *     Analog::handler (SedoxWPCatalogue\Dependencies\Analog\Handler\Syslog::init ('ident', 'facility'));
 */
class Syslog {
	public static $levels = array (
		\SedoxWPCatalogue\Dependencies\Analog\Analog::DEBUG    => LOG_DEBUG,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::INFO     => LOG_INFO,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::NOTICE   => LOG_NOTICE,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::WARNING  => LOG_WARNING,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::ERROR    => LOG_ERR,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::CRITICAL => LOG_CRIT,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::ALERT    => LOG_ALERT,
		\SedoxWPCatalogue\Dependencies\Analog\Analog::URGENT   => LOG_EMERG
	);

	public static $facilities = array (
		'auth'     => LOG_AUTH,
		'authpriv' => LOG_AUTHPRIV,
		'cron'     => LOG_CRON,
		'daemon'   => LOG_DAEMON,
		'kern'     => LOG_KERN,
		'lpr'      => LOG_LPR,
		'mail'     => LOG_MAIL,
		'news'     => LOG_NEWS,
		'syslog'   => LOG_SYSLOG,
		'user'     => LOG_USER,
		'uucp'     => LOG_UUCP
	);

	public static function init ($ident, $facility) {
		if (! defined ('PHP_WINDOWS_VERSION_BUILD')) {
			self::$facilities['local0'] = LOG_LOCAL0;
			self::$facilities['local1'] = LOG_LOCAL1;
			self::$facilities['local2'] = LOG_LOCAL2;
			self::$facilities['local3'] = LOG_LOCAL3;
			self::$facilities['local4'] = LOG_LOCAL4;
			self::$facilities['local5'] = LOG_LOCAL5;
			self::$facilities['local6'] = LOG_LOCAL6;
			self::$facilities['local7'] = LOG_LOCAL7;
		}

		if (array_key_exists (strtolower ($facility), self::$facilities)) {
			$facility = self::$facilities[strtolower ($facility)];
		} elseif (! in_array ($facility, array_values (self::$facilities), true)) {
			throw new \UnexpectedValueException ('Unknown facility value "' . $facility . '"');
		}

		return function ($info) use ($ident, $facility) {
			if (! openlog ($ident, LOG_PID, $facility)) {
				throw new \LogicException ('Can\'t open syslog for ident "' . $ident . '" and facility "' . $facility . '"');
			}

			syslog (Syslog::$levels[$info['level']], vsprintf ('%1$s: %4$s', $info));

			closelog ();
		};
	}
}
