<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\Utils;

class StringProvider
{
	public static function getTestToBool()
	{
		return [
			// $value, $expected, $message
			[1, true, '1 is true'],
			['true', true, 'true is true'],
			['yes', true, 'yes is true'],
			['on', true, 'on is true'],
			['enabled', true, 'enabled is true'],
			[0, false, '0 is false'],
			['false', false, 'false is false'],
			['no', false, 'no is false'],
			['off', false, 'off is false'],
			['disabled', false, 'disabled is false'],
			['foobar', true, 'foobar is true'],
			['', false, 'blank is false'],
		];
	}
}
