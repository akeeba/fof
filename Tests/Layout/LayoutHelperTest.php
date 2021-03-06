<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace FOF30\Tests\Layout;


use FOF30\Layout\LayoutHelper;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Helpers\TestJoomlaPlatform;

/**
 * @covers  FOF30\Layout\LayoutHelper::<protected>
 * @covers  FOF30\Layout\LayoutHelper::<private>
 */
class LayoutHelperTest extends FOFTestCase
{
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		// todo
	}

	public static function tearDownAfterClass()
	{
		TestJoomlaPlatform::$baseDirs = null;
		TestJoomlaPlatform::$template = null;
		TestJoomlaPlatform::$templateSuffixes = null;

		parent::tearDownAfterClass();
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		\JFactory::$application = $this->getMockCmsApp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * @covers       FOF30\Layout\LayoutHelper::render
	 *
	 * @dataProvider FOF30\Tests\Layout\LayoutHelperTestProvider::getTestRender
	 *
	 * @param string $layoutId       The layout to load
	 * @param array  $platformSetup  Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedOutput The expected output which should be returned
	 * @param string $message        Failure message
	 */
	public function testRender($layoutId, $platformSetup, $expectedOutput, $message)
	{
		// Set up the platform
		$defaultPlatformSetup = array(
			'baseDirs'         => null,
			'template'         => null,
			'templateSuffixes' => null
		);

		if (!is_array($platformSetup))
		{
			$platformSetup = array();
		}

		$platformSetup = array_merge($defaultPlatformSetup, $platformSetup);
		$reflector = new \ReflectionClass('FOF30\\Tests\\Helpers\\TestJoomlaPlatform');

		foreach ($platformSetup as $k => $v)
		{
			$reflector->setStaticPropertyValue($k, $v);
		}

		unset($reflector);

		// Set up a fake base
		$fakeBase = realpath(__DIR__ . '/../_data/layout/base');

		// Create the layout file object
		$actual = LayoutHelper::render(self::$container, $layoutId, null, $fakeBase);

		$this->assertEquals($expectedOutput, $actual, $message);
	}

	/**
	 * @covers       FOF30\Layout\LayoutHelper::render
	 *
	 * @dataProvider FOF30\Tests\Layout\LayoutHelperTestProvider::getTestRender
	 *
	 * @param string $layoutId       The layout to load
	 * @param array  $platformSetup  Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedOutput The expected output which should be returned
	 * @param string $message        Failure message
	 */
	public function testRenderDefaultBase($layoutId, $platformSetup, $expectedOutput, $message)
	{
		// Set up the platform
		$defaultPlatformSetup = array(
			'baseDirs'         => null,
			'template'         => null,
			'templateSuffixes' => null
		);

		if (!is_array($platformSetup))
		{
			$platformSetup = array();
		}

		$platformSetup = array_merge($defaultPlatformSetup, $platformSetup);
		$reflector = new \ReflectionClass('FOF30\\Tests\\Helpers\\TestJoomlaPlatform');

		foreach ($platformSetup as $k => $v)
		{
			$reflector->setStaticPropertyValue($k, $v);
		}

		unset($reflector);

		// Set up a fake base
		$fakeBase = realpath(__DIR__ . '/../_data/layout/base');

		// Create the layout file object
		LayoutHelper::$defaultBasePath = $fakeBase;
		$actual = LayoutHelper::render(self::$container, $layoutId);

		$this->assertEquals($expectedOutput, $actual, $message);
	}
}
