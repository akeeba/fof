<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace FOF40\Tests\View;

use FOF40\Render\Joomla;
use FOF40\Tests\Helpers\ApplicationTestCase;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Helpers\TestJoomlaPlatform;
use FOF40\Tests\Helpers\VfsHelper;
use FOF40\Tests\Stubs\View\DataView\RawStub;
use FOF40\View\ViewTemplateFinder;
use Joomla\CMS\Language\Text;
use org\bovigo\vfs\vfsStream;

class ViewTemplateFinderTest extends ApplicationTestCase
{
	public static $view = null;

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		TestJoomlaPlatform::$template = 'fake';

		self::$view = new RawStub(self::$container, [
			'name' => 'foobar',
		], [
			'getName' => function () {
				return 'foobar';
			},
		]);
	}

	public static function rebuildContainer($rendererClass = Joomla::class)
	{
		static::$container = null;
		static::$container = new TestContainer([
			'componentName' => 'com_fakeapp',
			'rendererClass' => $rendererClass,
		]);
	}

	/**
	 * @param   array $config
	 * @param   array $expected
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::constructProvider()
	 */
	public function testConstruct(array $config, array $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view, $config);

		if (array_key_exists('extensions', $expected))
		{
			self::assertEquals($expected['extensions'], $testObject->getExtensions());
		}

		if (array_key_exists('defaultLayout', $expected))
		{
			self::assertEquals($expected['defaultLayout'], $testObject->getDefaultLayout());
		}

		if (array_key_exists('defaultTpl', $expected))
		{
			self::assertEquals($expected['defaultTpl'], $testObject->getDefaultTpl());
		}

		if (array_key_exists('strictView', $expected))
		{
			self::assertEquals($expected['strictView'], $testObject->isStrictView());
		}

		if (array_key_exists('strictTpl', $expected))
		{
			self::assertEquals($expected['strictTpl'], $testObject->isStrictTpl());
		}

		if (array_key_exists('strictLayout', $expected))
		{
			self::assertEquals($expected['strictLayout'], $testObject->isStrictLayout());
		}

		if (array_key_exists('sidePrefix', $expected))
		{
			self::assertEquals($expected['sidePrefix'], $testObject->getSidePrefix());
		}
	}

	/**
	 * @param   string $setTo
	 * @param   string $expected
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::setSidePrefixProvider()
	 */
	public function testSetSidePrefix($setTo, $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view);
		$testObject->setSidePrefix($setTo);

		self::assertEquals($expected, $testObject->getSidePrefix());
	}

	/**
	 * @param   string $add
	 * @param   array  $expected
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::addExtensionProvider()
	 */
	public function testAddExtension($add, array $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view, [
			'extensions' => ['.foo'],
		]);
		$testObject->addExtension($add);

		self::assertEquals($expected, $testObject->getExtensions());
	}

	/**
	 * @param   string $remove
	 * @param   array  $expected
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::removeExtensionProvider()
	 */
	public function testRemoveExtension($remove, array $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view, [
			'extensions' => ['.foo', '.bar'],
		]);
		$testObject->removeExtension($remove);

		self::assertEquals($expected, $testObject->getExtensions());
	}

	/**
	 * @param   string $uri
	 * @param   array  $expected
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::parseTemplateUriProvider()
	 */
	public function testParseTemplateUri($uri, array $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view);
		$actual     = $testObject->parseTemplateUri($uri);

		self::assertEquals($expected, $actual);
	}

	/**
	 * @param   array $parameters
	 * @param   array $expected
	 *
	 * @throws \Exception
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::getViewTemplateUrisProvider()
	 */
	public function testGetViewTemplateUris(array $parameters, array $expected)
	{
		$testObject = new ViewTemplateFinder(static::$view);
		$actual     = $testObject->getViewTemplateUris($parameters);

		self::assertEquals($expected, $actual);
	}

	/**
	 * @param   array   $virtualFiles    The structure of files to create in the VFS.
	 * @param   string  $uri             The URI to parse
	 * @param   string  $expected        The expected VFS path
	 * @param   bool    $backend         Is this the backend? Default: no
	 * @param   string  $layoutTemplate  The layout template to use.
	 * @param   array   $extraPaths      The extra paths to look into.
	 * @param   array   $extraConfig     Config overrides for the ViewTemplateFinder.
	 *
	 * @dataProvider \FOF40\Tests\View\ViewTemplateFinderProvider::resolveUriToPathProvider()
	 */
	public function testResolveUriToPath(array $virtualFiles, $uri, $expected, $backend = false, $layoutTemplate = '', array $extraPaths = [], $extraConfig = [])
	{
		// Do I need to reset the Container?
		$rendererClass = Joomla::class;

		if (array_key_exists('rendererClass', $extraConfig))
		{
			$rendererClass = $extraConfig['rendererClass'];
			unset($extraConfig['rendererClass']);
		}

		if (!is_a(static::$container->rendererClass, $rendererClass))
		{
			self::rebuildContainer($rendererClass);
		}

		// Set up the virtual file system
		vfsStream::setup('siteroot', 0777, VfsHelper::createArrayDir($virtualFiles));

		// Set up the fake Platform
		/** @var TestJoomlaPlatform $platform */
		$platform = static::$container->platform;
		$platform->reset();

		TestJoomlaPlatform::$template                = 'fake';
		TestJoomlaPlatform::$isAdmin                 = $backend;
		TestJoomlaPlatform::$isCli                   = false;
		TestJoomlaPlatform::$getComponentBaseDirs    = function ($component) use ($backend) {
			return [
				'main'  => 'vfs://siteroot/' . ($backend ? 'admin' : 'site') . '/components/' . $component,
				'alt'   => 'vfs://siteroot/' . ($backend ? 'site' : 'admin') . '/components/' . $component,
				'site'  => 'vfs://siteroot/site/components/' . $component,
				'admin' => 'vfs://siteroot/admin/components/' . $component,
			];
		};
		TestJoomlaPlatform::$getTemplateOverridePath = function ($component, $absolute = true) {

			$path = 'html/' . $component;

			if (substr($component, 0, 7) == 'media:/')
			{
				$path = 'media/' . substr($component, 7);
			}

			$path = 'template/' . $path;

			return ($absolute ? 'vfs://siteroot/' : '') . $path;
		};
		TestJoomlaPlatform::$templateSuffixes        = [
			'.j99', '.j9',
		];

		// Get the ViewTemplateFinder object

		if ($expected === false)
		{
			self::expectExceptionMessage(Text::_('JLIB_APPLICATION_ERROR_LAYOUTFILE_NOT_FOUND'));
		}

		$testObject = new ViewTemplateFinder(static::$view, $extraConfig);
		$actual     = $testObject->resolveUriToPath($uri, $layoutTemplate, $extraPaths);

		if ($expected !== false)
		{
			self::assertEquals($expected, $actual);
		}
	}
}
