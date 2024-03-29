<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Download\Adapter;

/**
 * This file overrides certain core cURL functions inside the FOF40\Download\Adapter namespace. Because of the strange
 * way PHP handles calls to functions, the functions specified in this namespace override the core functions which are
 * implicitly defined in the global namespace. Therefore when the FOF40\Download\Adapter\Curl adapter calls, say,
 * curl_init PHP will execute FOF40\Download\Adapter\curl_init instead of the core, global curl_init function. This
 * allows us to mock libcurl for testing.
 */

use FOF40\Tests\Helpers\Download\FakeFopen;

global $fofTest_FakeFopen_Active;
$fofTest_FakeFopen_Active = false;

function file_get_contents($url, $flags = null, $context = null, $offset = null, $maxlen = null)
{
	global $fofTest_FakeFopen_Active;

	if (!$fofTest_FakeFopen_Active)
	{
		return \file_get_contents($url, $flags, $context, $offset, $maxlen);
	}

	return FakeFopen::file_get_contents($url, $flags, $context, $offset, $maxlen);
}
