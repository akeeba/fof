<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Template;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestJoomlaPlatform;
use JFolder;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory as JoomlaFactory;
use ReflectionProperty;

/**
 * Class TemplateTest
 *
 * @package FOF40\Tests\Template
 */
class TemplateTest extends FOFTestCase
{
	private $_stashedServer = [];

	protected function setUp()
	{
		parent::setUp();

		// Force a HtmlDocument instance
		$this->saveFactoryState();
		JoomlaFactory::$document = Document::getInstance('html');

		// Fake the server variables to get Uri working right
		global $_SERVER;
		$this->_stashedServer   = $_SERVER;
		$_SERVER['HTTP_HOST']   = 'www.example.com';
		$_SERVER['REQUEST_URI'] = '/index.php?option=com_foobar';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		// Fake the session
		JoomlaFactory::$session = $this->getMockSession();
		$application       = JoomlaFactory::getApplication('site');

		// Reset the fake platform
		TestJoomlaPlatform::$template         = null;
		TestJoomlaPlatform::$baseDirs         = null;
		TestJoomlaPlatform::$isAdmin          = null;
		TestJoomlaPlatform::$isCli            = null;
		TestJoomlaPlatform::$templateSuffixes = null;

		// Reset Uri
		$reflector = new \ReflectionClass('\Joomla\CMS\Uri\Uri');

		$instancesProp = $reflector->getProperty('instances');
		$instancesProp->setAccessible(true);
		$instancesProp->setValue([]);

		$rootProp = $reflector->getProperty('root');
		$rootProp->setAccessible(true);
		$rootProp->setValue([]);

		// Fake the template
		$template  = (object) [
			'template' => 'fake_test_template',
		];
		$attribute = new ReflectionProperty($application, 'template');
		$attribute->setAccessible(true);
		$attribute->setValue($application, $template);
	}

	protected function tearDown()
	{
		// Restore the JoomlaFactory
		$this->restoreFactoryState();

		// Reset the application template
		$application = JoomlaFactory::getApplication('site');
		$attribute   = new ReflectionProperty($application, 'template');
		$attribute->setAccessible(true);
		$attribute->setValue($application, null);

		// Restore the $_SERVER global
		global $_SERVER;
		$_SERVER = $this->_stashedServer;

		// Call the parent
		parent::tearDown();
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		// Create our fake template and template overrides
		JFolder::copy(JPATH_TESTS . '/_data/fake_test_template', JPATH_THEMES . '/fake_test_template', '', true);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();

		// Remove the fake template
		JFolder::delete(JPATH_THEMES . '/fake_test_template');
	}

	/**
	 * Test to addCSS method
	 *
	 * @param   string $path    CSS path to add
	 * @param   string $expect  Rendered CSS path to expect
	 * @param   string $message Message on failure
	 *
	 * @return  void
	 *
	 * @dataProvider getTestAddCSS
	 */
	public function testAddCSS($path, $expect, $message)
	{
		$document = static::$container->platform->getDocument();
		static::$container->template->addCSS($path);

		$styleSheets = $this->readAttribute($document, '_styleSheets');

		$this->assertArrayHasKey($expect, $styleSheets, $message);
	}

	public function getTestAddCSS()
	{
		return [
			[
				'media://com_foobar/css/anothertest.css', 'http://www.example.com/media/com_foobar/css/anothertest.css',
				'media:// should be changed into media location',
			],
			[
				'media://com_foobar/css/test.css',
				'http://www.example.com/templates/fake_test_template/media/com_foobar/css/test.css',
				'media:// overrides should be taken into account',
			],
			[
				'admin://com_foobar/css/anothertest.css',
				'http://www.example.com/administrator/com_foobar/css/anothertest.css',
				'admin:// should be changed into administrator path',
			],
			[
				'admin://com_foobar/css/test.css',
				'http://www.example.com/administrator/com_foobar/css/anothertest.css',
				'admin:// should not be overriden',
			],
			[
				'site://com_foobar/css/anothertest.css', 'http://www.example.com/com_foobar/css/anothertest.css',
				'site:// should be changed into site path',
			],
			[
				'site://com_foobar/css/test.css', 'http://www.example.com/com_foobar/css/anothertest.css',
				'site:// should not be overriden',
			],
		];
	}

	/**
	 * Test to addJS method
	 *
	 * @param   string $path    CSS path to add
	 * @param   string $expect  Rendered CSS path to expect
	 * @param   string $message Message on failure
	 *
	 * @return  void
	 *
	 * @dataProvider getTestAddJS
	 */
	public function testAddJS($path, $expect, $message)
	{
		$document = static::$container->platform->getDocument();
		static::$container->template->addJS($path);

		$scripts = $this->readAttribute($document, '_scripts');

		$this->assertArrayHasKey($expect, $scripts, $message);
	}

	public function getTestAddJS()
	{
		return [
			[
				'media://com_foobar/js/anothertest.js', 'http://www.example.com/media/com_foobar/js/anothertest.js',
				'media:// should be changed into media location',
			],
			[
				'media://com_foobar/js/test.js',
				'http://www.example.com/templates/fake_test_template/media/com_foobar/js/test.js',
				'media:// overrides should be taken into account',
			],
			[
				'admin://com_foobar/js/anothertest.js',
				'http://www.example.com/administrator/com_foobar/js/anothertest.js',
				'admin:// should be changed into administrator path',
			],
			[
				'admin://com_foobar/js/test.js', 'http://www.example.com/administrator/com_foobar/js/anothertest.js',
				'admin:// should not be overriden',
			],
			[
				'site://com_foobar/js/anothertest.js', 'http://www.example.com/com_foobar/js/anothertest.js',
				'site:// should be changed into site path',
			],
			[
				'site://com_foobar/js/test.js', 'http://www.example.com/com_foobar/js/anothertest.js',
				'site:// should not be overriden',
			],
		];
	}

	/**
	 * Test to parsePath method
	 *
	 * @dataProvider getTestParsePath
	 */
	public function testParsePath($path, $localfile, $expect, $message)
	{
		$fullurl = static::$container->template->parsePath($path, $localfile);

		$expect  = realpath($expect);
		$fullurl = realpath($fullurl);

		$this->assertEquals(
			$expect,
			$fullurl,
			$message
		);
	}

	public function getTestParsePath()
	{
		return [
			[
				'media://com_foobar/css/test.css', false,
				'http://www.example.com/templates/fake_test_template/media/com_foobar/css/test.css',
				'media:// should be changed into media location and should be retrieved from template as override exists',
			],
			[
				'media://com_foobar/css/test2.css', false, 'http://www.example.com/media/com_foobar/css/test2.css',
				'media:// should be changed into media location',
			],
			[
				'media://com_foobar/css/test.css', true,
				JPATH_THEMES . '/fake_test_template/media/com_foobar/css/test.css',
				'media:// should be changed into media location and should be retrieved from template as override exists',
			],
		];
	}


	/**
	 * Test to getAltPaths method
	 *
	 * @dataProvider getTestGetAltPaths
	 */
	public function testGetAltPaths($path, $expect, $normal, $message)
	{
		$altpath = static::$container->template->getAltPaths($path);
		$this->assertEquals(
			$expect,
			$altpath[$normal],
			$message
		);
	}

	public function getTestGetAltPaths()
	{
		return [
			[
				'media://com_foobar/css/test.css', 'media/com_foobar/css/test.css', 'normal',
				'media:// should be changed into media location',
			],
			[
				'admin://com_foobar/css/test.css', 'administrator/com_foobar/css/test.css', 'normal',
				'admin:// should be changed into administrator path',
			],
			[
				'site://com_foobar/css/test.css', 'com_foobar/css/test.css', 'normal',
				'site:// should be changed into site path',
			],
			[
				'media://com_foobar/css/test.css', 'templates/fake_test_template/media/com_foobar/css/test.css',
				'alternate', 'media:// alternate link should be changed into templates media path',
			],
		];
	}

	/**
	 * Test to route method
	 *
	 * @dataProvider getTestRoute
	 */
	public function testRoute($url, $expect, $message)
	{
		$config = JoomlaFactory::getConfig();
		$config->set('sef', 1); // Required by these tests
		$fullurl = static::$container->template->route($url);
		$this->assertEquals(
			$expect,
			$fullurl,
			$message
		);
	}

	public function getTestRoute()
	{
		return [
			['index.php', '/index.php', 'Any URL starting with index.php should return itself'],
			['index.php?option=com_foobar&view=foo', '/index.php/component/foobar/?view=foo', 'Basic SEF URLs'],
			[
				'view=categories&layout=tree', '/index.php/component/foobar/?view=categories&amp;layout=tree',
				'Any new variables passed in should override the current URL',
			],
			[
				'view=cpanel&layout=default&format=json',
				'/index.php/component/foobar/?view=cpanel&amp;layout=default&amp;format=json',
				'A non-html format should be appended to the URL',
			],
		];
	}
}
