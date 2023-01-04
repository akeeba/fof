<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Utils;

use FOF40\IP\IPHelper;

class IpStub extends IpHelper
{
	public static $fakeIP = null;

	protected static function detectIP(): string
	{
		if (!is_null(static::$fakeIP))
		{
			return static::$fakeIP;
		}

		return parent::detectIP();
	}

	public static function detectAndCleanIP(): string
	{
		return parent::detectAndCleanIP();
	}
}
