<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Layout;


use FOF40\Layout\LayoutHelper;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestJoomlaPlatform;
use Joomla\CMS\Factory as JoomlaFactory;

/**
 * @covers  FOF40\Layout\LayoutHelper::<protected>
 * @covers  FOF40\Layout\LayoutHelper::<private>
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
		TestJoomlaPlatform::$baseDirs         = null;
		TestJoomlaPlatform::$template         = null;
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

		JoomlaFactory::$application = $this->getMockCmsApp();
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
	 * @covers       \FOF40\Layout\LayoutHelper::render
	 *
	 * @dataProvider \FOF40\Tests\Layout\LayoutHelperTestProvider::getTestRender
	 *
	 * @param string $layoutId       The layout to load
	 * @param array  $platformSetup  Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedOutput The expected output which should be returned
	 * @param string $message        Failure message
	 */
	public function testRender($layoutId, $platformSetup, $expectedOutput, $message)
	{
		// Set up the platform
		$defaultPlatformSetup = [
			'baseDirs'         => null,
			'template'         => null,
			'templateSuffixes' => null,
		];

		if (!is_array($platformSetup))
		{
			$platformSetup = [];
		}

		$platformSetup = array_merge($defaultPlatformSetup, $platformSetup);
		$reflector     = new \ReflectionClass('FOF40\\Tests\\Helpers\\TestJoomlaPlatform');

		foreach ($platformSetup as $k => $v)
		{
			$reflector->setStaticPropertyValue($k, $v);
		}

		unset($reflector);

		// Set up a fake layout
		$fakeBase = realpath(__DIR__ . '/../_data/layout/layout');

		// Create the layout file object
		$actual = LayoutHelper::render(self::$container, $layoutId, [], $fakeBase);

		$this->assertEquals($expectedOutput, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Layout\LayoutHelper::render
	 *
	 * @dataProvider \FOF40\Tests\Layout\LayoutHelperTestProvider::getTestRender
	 *
	 * @param string $layoutId       The layout to load
	 * @param array  $platformSetup  Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedOutput The expected output which should be returned
	 * @param string $message        Failure message
	 */
	public function testRenderDefaultBase($layoutId, $platformSetup, $expectedOutput, $message)
	{
		// Set up the platform
		$defaultPlatformSetup = [
			'baseDirs'         => null,
			'template'         => null,
			'templateSuffixes' => null,
		];

		if (!is_array($platformSetup))
		{
			$platformSetup = [];
		}

		$platformSetup = array_merge($defaultPlatformSetup, $platformSetup);
		$reflector     = new \ReflectionClass('FOF40\\Tests\\Helpers\\TestJoomlaPlatform');

		foreach ($platformSetup as $k => $v)
		{
			$reflector->setStaticPropertyValue($k, $v);
		}

		unset($reflector);

		// Set up a fake layout
		$fakeBase = realpath(__DIR__ . '/../_data/layout/layout');

		// Create the layout file object
		LayoutHelper::$defaultBasePath = $fakeBase;
		$actual                        = LayoutHelper::render(self::$container, $layoutId);

		$this->assertEquals($expectedOutput, $actual, $message);
	}
}
