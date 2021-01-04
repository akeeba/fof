<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Helpers;

use FOF40\Container\Container;

/**
 * Base class for tests requiring a container and/or an application to be set up
 *
 * @package Awf\Tests\Helpers
 */
abstract class ApplicationTestCase extends \PHPUnit_Framework_TestCase
{
	/** @var Container A container suitable for unit testing */
	public static $container = null;

	public static function setUpBeforeClass()
	{
		static::rebuildContainer();
	}

	public static function tearDownAfterClass()
	{
		$platform = static::$container->platform;

		if ($platform instanceof TestJoomlaPlatform)
		{
			$platform->reset();
		}

		static::$container = null;
	}

	public static function rebuildContainer()
	{
		static::$container = null;
		static::$container = new TestContainer([
			'componentName' => 'com_fakeapp',
		]);
	}
}
