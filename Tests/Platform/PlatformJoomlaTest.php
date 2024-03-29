<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Platform;


use FOF40\Platform\Joomla\Platform;
use FOF40\Tests\Helpers\Application\MockLanguage;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\MockSession;
use FOF40\Tests\Helpers\Platform\UserForAdminAuth;
use FOF40\Tests\Helpers\ReflectionHelper;
use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Version as JoomlaVersion;

/**
 * @covers \FOF40\Platform\Joomla\Platform::<protected>
 * @covers \FOF40\Platform\Joomla\Platform::<private>
 */
class PlatformJoomlaTest extends FOFTestCase
{
	/** @var Platform The object being tested */
	protected $platform = null;

	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();
		$this->platform = new Platform(static::$container);
	}

	protected function tearDown()
	{
		$this->restoreFactoryState();
		MockSession::$user = null;

		parent::tearDown();
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::checkExecution
	 */
	public function testCheckExecution()
	{
		$this->assertTrue($this->platform->checkExecution());
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::raiseError
	 */
	public function testRaiseError()
	{
		$this->setExpectedException('\Exception', 'Test', 123);

		$this->platform->raiseError(123, 'Test');
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::isCli
	 * @covers       \FOF40\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestIsCli
	 */
	public function testIsCli($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isCli();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::isBackend
	 * @covers       \FOF40\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestIsBackend
	 */
	public function testIsBackend($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isBackend();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::isFrontend
	 * @covers       \FOF40\Platform\Joomla\Platform::isCliAdmin
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestIsFrontend
	 */
	public function testIsFrontend($mockApplicationType, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType);

		$actual = $this->platform->isFrontend();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @param $mockApplicationType
	 */
	private function forceApplicationTypeAndResetPlatformCliAdminCache($mockApplicationType)
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';
		$config               = JoomlaFactory::getConfig(JPATH_SITE . '/configuration.php');
		JoomlaFactory::$session   = $this->getMockSession();

		// Get the correct mock application
		switch ($mockApplicationType)
		{
			case 'cli':
				$mockApplication = new CliApplication();
				break;

			case 'site':
			default:
				$mockApplication = new SiteApplication(null, $config);
				break;

			case 'admin':
				$mockApplication = new AdministratorApplication(null, $config);
				break;

			case 'exception':
				$mockApplication = new \Exception('This is not an application');
		}

		// Set the mock application to JoomlaFactory
		JoomlaFactory::$application = $mockApplication;

		// Reset Platform's internal cache
		$reflector = new \ReflectionClass('FOF40\\Platform\\Joomla\\Platform');
		$propIsCli = $reflector->getProperty('isCLI');
		$propIsCli->setAccessible(true);
		$propIsCli->setValue(null);
		$propIsAdmin = $reflector->getProperty('isAdmin');
		$propIsAdmin->setAccessible(true);
		$propIsAdmin->setValue(null);
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getPlatformBaseDirs
	 */
	public function testGetPlatformBaseDirs()
	{
		$baseDirs = $this->platform->getPlatformBaseDirs();

		$expectedDirs = [
			'root'   => JPATH_ROOT,
			'public' => JPATH_ROOT,
			'admin'  => JPATH_ROOT . '/administrator',
			'tmp'    => JPATH_ROOT . '/tmp',
			'log'    => JPATH_ROOT . '/logs',
		];

		$this->assertInternalType('array', $baseDirs);

		$expectedDirs = array_map('realpath', $expectedDirs);
		$baseDirs     = array_map('realpath', $baseDirs);

		foreach ($expectedDirs as $k => $v)
		{
			$this->assertArrayHasKey($k, $baseDirs, "Platform directories must contain $k key");
			$this->assertEquals($v, $baseDirs[$k], "Platform directories key $k must have value $v");
		}
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::getComponentBaseDirs
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestComponentBaseDirs
	 */
	public function testGetComponentBaseDirs($area, $expectedMain, $expectedAlt)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($area);

		$actual = $this->platform->getComponentBaseDirs('com_foobar');

		$this->assertInternalType('array', $actual);

		$expected = [
			'site'  => JPATH_SITE . '/components/com_foobar',
			'admin' => JPATH_SITE . '/administrator/components/com_foobar',
			'main'  => JPATH_SITE . '/' . $expectedMain,
			'alt'   => JPATH_SITE . '/' . $expectedAlt,
		];

		$actual   = array_map('realpath', $actual);
		$expected = array_map('realpath', $expected);

		foreach ($expected as $k => $v)
		{
			$this->assertArrayHasKey($k, $actual, "Component directories must contain $k key");
			$this->assertEquals($v, $actual[$k], "Platform directories key $k must have value $v with app type $area ");
		}
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getTemplate
	 */
	public function testGetTemplate()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		JoomlaFactory::$session = $this->getMockSession();

		$expected = JoomlaFactory::getApplication('site')->getTemplate();
		$actual   = $this->platform->getTemplate();

		$this->assertEquals($expected, $actual, "getTemplate() must return the application's template");
	}


	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getUser
	 */
	public function testGetUser()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		$fakeSession        = $this->getMockSession();
		JoomlaFactory::$session = $fakeSession;

		// Required to let JoomlaFactory know which application to load
		JoomlaFactory::getApplication('site');

		$actual = $this->platform->getUser();

		$user = JoomlaFactory::$session->get('user');

		$this->assertInstanceOf('\Joomla\CMS\User\User', $actual, "getUser() must return a \Joomla\CMS\User\User object");
		$this->assertEquals($user, $actual, "getUser() must return the requested user object");
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getDocument
	 */
	public function testGetDocument()
	{
		$_SERVER['HTTP_HOST'] = 'www.example.com';

		$fakeSession        = $this->getMockSession();
		JoomlaFactory::$session = $fakeSession;

		// Required to let JoomlaFactory know which application to load
		JoomlaFactory::getApplication('site');

		$expected = JoomlaFactory::getDocument();

		$reflector = new \ReflectionClass('FOF40\\Platform\\Joomla\\Platform');
		$propIsCli = $reflector->getProperty('isCLI');
		$propIsCli->setAccessible(true);
		$propIsCli->setValue(true);
		$propIsAdmin = $reflector->getProperty('isAdmin');
		$propIsAdmin->setAccessible(true);
		$propIsAdmin->setValue(false);

		// CLI app: null document
		$actual = $this->platform->getDocument();
		$this->assertNull($actual, "CLI app must return a null document");

		$propIsCli->setValue(false);
		$actual = $this->platform->getDocument();

		$this->assertInstanceOf('\Joomla\CMS\Document\Document', $actual, "getDocument() must return a JDocument object");
		$this->assertEquals($expected, $actual, "getDocument() must return the document from JoomlaFactory");
	}


	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getLanguage
	 *
	 */
	public function testGetLanguage()
	{
		$expected = JoomlaFactory::getLanguage();
		$actual   = $this->platform->getLanguage();

		$this->assertInstanceOf(Language::class, $actual, "getLanguage() must return a Language object");
		$this->assertEquals($expected, $actual, "getLanguage() must return the language object from Factory");
	}

	/**
	 * @covers \FOF40\Platform\Joomla\Platform::getDbo
	 *
	 */
	public function testGetDbo()
	{
		$expected = JoomlaFactory::getDbo();
		$actual   = $this->platform->getDbo();

		$this->assertInstanceOf('\JDatabaseDriver', $actual, "getDbo() must return a JDatabaseDriver object");
		$this->assertEquals($expected, $actual, "getDbo() must return the database object from JoomlaFactory");
	}

	/**
	 * @covers          \FOF40\Platform\Joomla\Platform::getTemplateSuffixes
	 *
	 * @XXXdataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestGetTemplateSuffixes
	 *
	 */
	public function testGetTemplateSuffixes()
	{
		$jversion = new JoomlaVersion;

		if (substr($jversion->RELEASE, 0, 2) != '3.')
		{
			$this->markTestIncomplete('testGetTemplateSuffixes will only run on Joomla! 3');
		}

		$expected = [
			'.j3' . substr($jversion->RELEASE, 2),
			'.j3',
		];

		$actual = $this->platform->getTemplateSuffixes();

		$this->assertEquals($expected, $actual, "getTemplateSuffixes must return two suffixes, for the minor and major Joomla! CMS release");
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::getTemplateOverridePath
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestGetTemplateOverridePath
	 *
	 */
	public function testGetTemplateOverridePath($applicationType, $component, $absolute, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($applicationType);

		if ($applicationType != 'cli')
		{
			$app          = JoomlaFactory::getApplication();
			$fakeTemplate = (object) [
				'template' => 'system',
			];
			ReflectionHelper::setValue($app, 'template', $fakeTemplate);
		}

		$actual = $this->platform->getTemplateOverridePath($component, $absolute);

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::loadTranslations
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestLoadTranslations
	 *
	 */
	public function testLoadTranslations($appType, $regularPath, $otherPath, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$mockLanguage                  = MockLanguage::create($this);
		MockLanguage::$loadedLanguages = [];
		JoomlaFactory::$language           = $mockLanguage;

		$this->platform->loadTranslations('com_foobar');

		$expected = [
			// $extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false, $default = true
			['com_foobar', $regularPath, 'en-GB', true, true],
			['com_foobar', $regularPath, null, true, true],
			['com_foobar', $otherPath, 'en-GB', true, true],
			['com_foobar', $otherPath, null, true, true],
		];

		$this->assertEquals($expected, MockLanguage::$loadedLanguages, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::authorizeAdmin
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestAuthorizeAdmin
	 *
	 */
	public function testAuthorizeAdmin($appType, $auths, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$fakeUser               = new UserForAdminAuth();
		$fakeUser->allowedAuths = $auths;
		MockSession::$user      = $fakeUser;

		$actual = $this->platform->authorizeAdmin('com_foobar');

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::authorise
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestAuthorise
	 *
	 */
	public function testAuthorise($appType, $auths, $assetName, $action, $expected, $message)
	{
		$this->forceApplicationTypeAndResetPlatformCliAdminCache($appType);

		$fakeUser               = new UserForAdminAuth();
		$fakeUser->allowedAuths = $auths;
		MockSession::$user      = $fakeUser;

		$actual = $this->platform->authorise($action, $assetName);

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @covers       \FOF40\Platform\Joomla\Platform::getDate
	 *
	 * @dataProvider \FOF40\Tests\Platform\PlatformJoomlaProvider::getTestDate
	 */
	public function testGetDate($test)
	{
		$date  = $this->platform->getDate($test['time'], $test['offset'], $test['locale']);
		$case  = $test['case'];
		$class = $test['intended_class'];

		$this->assertInstanceOf('FOF40\Date\Date', $date, "$case -- getDate must return a Date-compatible object");
		$this->assertInstanceOf('DateTime', $date, "$case -- getDate must return a DateTime-compatible object");
		$this->assertInstanceOf($class, $date, "$case -- getDate must return a $class object");

		if ($class == 'FOF40\Date\DateDecorator')
		{
			$oRef = new \ReflectionObject($date);
			$pRef = new \ReflectionProperty($date, 'decorated');
			$pRef->setAccessible(true);
			$decorated = $pRef->getValue($date);

			$this->assertInstanceOf('FOF40\Date\Date', $decorated, "$case -- The decorated property must be a \FOF40\Date\Date object");
		}
	}
}
