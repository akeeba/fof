<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Layout;


use FOF40\Layout\LayoutFile;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestJoomlaPlatform;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\Registry\Registry;

/**
 * @covers  FOF40\Layout\LayoutFile::<protected>
 * @covers  FOF40\Layout\LayoutFile::<private>
 */
class LayoutFileTest extends FOFTestCase
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
	 * @covers       FOF40\Layout\LayoutFile::getPath
	 *
	 * @dataProvider FOF40\Tests\Layout\LayoutFileTestProvider::getTestGetPath
	 *
	 * @param string $layoutId      The layout to load
	 * @param array  $platformSetup Platform setup (baseDirs, template, templateSuffixes)
	 * @param string $expectedPath  The expected path which should be returned
	 * @param string $message       Failure message
	 */
	public function testGetPath($layoutId, $platformSetup, $expectedPath, $message)
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

		// Set up a fake options Registry object
		$fakeOptions = new Registry([
			'option' => 'com_foobar',
			'client' => 0,
		]);

		$fakeBase = realpath(__DIR__ . '/../_data/layout/layout');

		// Create the layout file object
		$layoutFile            = new LayoutFile($layoutId, $fakeBase, $fakeOptions);
		$layoutFile->container = static::$container;

		// Call the protected method. Dirty, but that's what we have to test without loading and displaying an actual
		// PHP file.
		$actual = ReflectionHelper::invoke($layoutFile, 'getPath');

		$expectedPath = realpath($expectedPath);
		$actual       = realpath($actual);

		$this->assertEquals($expectedPath, $actual, $message);
	}
}
