<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Timer;

/**
 * Utility class to fake the microtime intrinsic function
 */
class FakeTimer
{
	/** @var null The microtime returned by this class */
	public static $microtime = null;

	public static function microtime($get_as_float = null)
	{
		if (empty(static::$microtime))
		{
			self::syncMicrotime();
		}

		if ($get_as_float)
		{
			return static::$microtime;
		}

		$int     = sprintf('%d', floor(static::$microtime));
		$decimal = sprintf('%0.6f', static::$microtime - floor(static::$microtime));

		return $decimal . ' ' . $int;
	}

	public static function applyDelta($delta)
	{
		static::$microtime += $delta;
	}

	public static function syncMicrotime()
	{
		static::$microtime = \microtime(true);
	}
}
