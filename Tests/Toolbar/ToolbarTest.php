<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Toolbar;

use FOF40\Input\Input;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\DataModelStub;
use FOF40\Tests\Stubs\Toolbar\ToolbarStub;
use FOF40\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

require_once 'ToolbarDataprovider.php';
require_once JPATH_TESTS . '/Stubs/Joomla/JToolbarHelper.php';

/**
 * @covers  FOF40\Toolbar\Toolbar::<protected>
 * @covers  FOF40\Toolbar\Toolbar::<private>
 */
class ToolbarTest extends FOFTestCase
{
	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::__construct
	 */
	public function test__construct()
	{
		$msg    = 'Toolbar::__construct %s';
		$config = [
			'renderFrontendButtons' => true,
			'renderFrontendSubmenu' => true,
		];

		$platform = static::$container->platform;
		// Otherwise the toolbar will try to load the js framework
		$platform::$isCli     = true;
		$platform::$authorise = function () {
			// To keep it simply, the user has access to everything
			return true;
		};

		$toolbar = new Toolbar(static::$container, $config);

		$perms   = ReflectionHelper::getValue($toolbar, 'perms');
		$buttons = ReflectionHelper::getValue($toolbar, 'renderFrontendButtons');
		$submenu = ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu');

		$checkPerms = (object) [
			'manage'    => true,
			'create'    => true,
			'edit'      => true,
			'editstate' => true,
			'delete'    => true,
		];

		$this->assertTrue($buttons, sprintf($msg, 'Failed to set the frontend buttons flag'));
		$this->assertTrue($submenu, sprintf($msg, 'Failed to set the frontend submenu flag'));
		$this->assertEquals($checkPerms, $perms, sprintf($msg, 'Failed to set the permissions'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::renderToolbar
	 * @dataProvider    ToolbarDataprovider::getTestRenderToolbar
	 */
	public function testRenderToolbar($test, $check)
	{
		$msg = 'Toolbar::renderToolbar %s - Case: ' . $check['case'];

		$controller = $this->getMockBuilder('\FOF40\Tests\Stubs\Controller\ControllerStub')
			->setMethods(['getName', 'getTask'])
			->setConstructorArgs([static::$container])
			->getMock();

		$controller->method('getName')->willReturn($test['mock']['getName']);
		$controller->method('getTask')->willReturn($test['mock']['getTask']);

		$dispacher = $this->getMockBuilder('FOF40\Dispatcher\Dispatcher')
			->setMethods(['getController'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispacher->method('getController')
			->willReturn($test['mock']['getController'] ? $controller : null);

		$appConfig = $this->getMockBuilder('FOF40\Configuration\Configuration')
			->setMethods(['get'])
			->setConstructorArgs([])
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		$appConfig->method('get')->willReturnCallback(function ($something) use ($test, $check) {
			if (strrpos($something, 'renderFrontendButtons') !== false)
			{
				return false;
			}

			if ($something == $check['config'])
			{
				return $test['mock']['config'];
			}
		});

		$container = new TestContainer([
			'input'      => new Input($test['input']),
			'dispatcher' => $dispacher,
			'appConfig'  => $appConfig,
		]);

		$toolbar = new ToolbarStub($container);

		ReflectionHelper::setValue($toolbar, 'useConfigurationFile', $test['useConfig']);

		$toolbar->renderToolbar($test['view'], $test['task']);

		$methods = $toolbar->methodCounter;

		$this->assertEquals($check['counter'], $methods, sprintf($msg, 'Failed to correctly invoke "on" methods'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::onCpanelsBrowse
	 * @dataProvider    ToolbarDataprovider::getTestOnCpanelsBrowse
	 */
	public function testOnCpanelsBrowse($test, $check)
	{
		$msg = 'Toolbar::onCpanelsBrowse %s - Case: ' . $check['case'];

		ToolbarHelper::resetMethods();

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['mock']['isAdmin'];

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['renderSubmenu', 'isDataView'])
			->setConstructorArgs([static::$container])
			->getMock();
		$toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
		$toolbar->method('isDataView')->willReturn($test['mock']['dataView']);

		ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

		$toolbar->onCpanelsBrowse();

		$methods = ToolbarHelper::$methodCounter;

		$this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke ToolbarHelper methods'));
	}

	/**
	 * @group           Toolbar
	 * @group           ToolbarOnBrowse
	 * @covers          FOF40\Toolbar\Toolbar::onBrowse
	 * @dataProvider    ToolbarDataprovider::getTestOnBrowse
	 */
	public function testOnBrowse($test, $check)
	{
		$msg = 'Toolbar::onBrowse %s - Case: ' . $check['case'];

		ToolbarHelper::resetMethods();

		$TestContainer = static::$container;
		$options       = [];

		if ($test['model'])
		{
			$options['factory'] = new ClosureHelper([
				'model' => function () use ($test, $TestContainer) {

					if ($test['model'] == 'specialfields')
					{
						$config = [
							'idFieldName' => 'foftest_foobar_id',
							'tableName'   => '#__foftest_foobars',
						];
					}
					else
					{
						$config = [
							'idFieldName' => 'foftest_bare_id',
							'tableName'   => '#__foftest_bares',
						];
					}

					return new DataModelStub($TestContainer, $config);
				},
			]);
		}

		$container = new TestContainer($options);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['mock']['isAdmin'];

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['renderSubmenu', 'isDataView'])
			->setConstructorArgs([$container])
			->getMock();

		$toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
		$toolbar->method('isDataView')->willReturn($test['mock']['dataView']);

		ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);
		ReflectionHelper::setValue($toolbar, 'perms', (object) $test['perms']);

		$toolbar->onBrowse();

		$methods = ToolbarHelper::$methodCounter;

		$this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke ToolbarHelper methods'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::onRead
	 * @dataProvider    ToolbarDataprovider::getTestOnRead
	 */
	public function testOnRead($test, $check)
	{
		$msg = 'Toolbar::onRead %s - Case: ' . $check['case'];

		ToolbarHelper::resetMethods();

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['mock']['isAdmin'];

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['renderSubmenu', 'isDataView'])
			->setConstructorArgs([static::$container])
			->getMock();

		$toolbar->expects($check['submenu'] ? $this->once() : $this->never())->method('renderSubmenu');
		$toolbar->method('isDataView')->willReturn($test['mock']['dataView']);

		ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', $test['submenu']);
		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

		$toolbar->onRead();

		$methods = ToolbarHelper::$methodCounter;

		$this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke ToolbarHelper methods'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::onAdd
	 * @dataProvider    ToolbarDataprovider::getTestOnAdd
	 */
	public function testOnAdd($test, $check)
	{
		$msg = 'Toolbar::onAdd %s - Case: ' . $check['case'];

		ToolbarHelper::resetMethods();

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['mock']['isAdmin'];

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['isDataView'])
			->setConstructorArgs([static::$container])
			->getMock();

		$toolbar->method('isDataView')->willReturn($test['mock']['dataView']);

		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);
		ReflectionHelper::setValue($toolbar, 'perms', (object) $test['perms']);

		$toolbar->onAdd();

		$methods = ToolbarHelper::$methodCounter;

		$this->assertEquals($check['methods'], $methods, sprintf($msg, 'Failed to invoke ToolbarHelper methods'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::onEdit
	 * @dataProvider    ToolbarDataprovider::getTestOnEdit
	 */
	public function testOnEdit($test, $check)
	{
		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['mock']['isAdmin'];

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['onAdd'])
			->setConstructorArgs([static::$container])
			->getMock();

		$toolbar->expects($check['onAdd'] ? $this->once() : $this->never())->method('onAdd');

		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', $test['buttons']);

		$toolbar->onEdit();
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::clearLinks
	 */
	public function testClearLinks()
	{
		$toolbar = new ToolbarStub(static::$container);

		ReflectionHelper::setValue($toolbar, 'linkbar', [1, 2, 3]);

		$toolbar->clearLinks();

		$this->assertEmpty(ReflectionHelper::getValue($toolbar, 'linkbar'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::getLinks
	 */
	public function testGetLinks()
	{
		$toolbar = new ToolbarStub(static::$container);
		$links   = [1, 2, 3];

		ReflectionHelper::setValue($toolbar, 'linkbar', $links);

		$result = $toolbar->getLinks();

		$this->assertEquals($links, $result);
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::appendLink
	 * @dataProvider    ToolbarDataprovider::getTestAppendLink
	 */
	public function testAppendLink($test, $check)
	{
		$msg = 'Toolbar::appendLink %s - Case: ' . $check['case'];

		$toolbar = new ToolbarStub(static::$container);

		ReflectionHelper::setValue($toolbar, 'linkbar', $test['mock']['linkbar']);

		$toolbar->appendLink($test['name'], $test['link'], $test['active'], $test['icon'], $test['parent']);

		$linkbar = ReflectionHelper::getValue($toolbar, 'linkbar');

		$this->assertEquals($check['linkbar'], $linkbar, sprintf($msg, 'Failed to correctly build the links'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::prefixLink
	 */
	public function testPrefixLink()
	{
		$toolbar = new ToolbarStub(static::$container);

		ReflectionHelper::setValue($toolbar, 'linkbar', ['some', 'links']);

		$link = ['name' => 'foobar', 'link' => null, 'active' => false, 'icon' => ''];

		$toolbar->prefixLink('foobar');

		$linkbar = ReflectionHelper::getValue($toolbar, 'linkbar');

		$this->assertEquals($link, array_shift($linkbar));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::renderSubmenu
	 * @dataProvider    ToolbarDataprovider::getTestRenderSubmenu
	 */
	public function testRenderSubmenu($test, $check)
	{
		$msg     = 'Toolbar::renderSubmenu %s - Case: ' . $check['case'];
		$checker = [];

		$container = new TestContainer([
			'input' => new Input($test['input']),
		]);

		$toolbar = $this->getMockBuilder('FOF40\Tests\Stubs\Toolbar\ToolbarStub')
			->setMethods(['getMyViews', 'appendLink'])
			->setConstructorArgs([$container])
			->getMock();

		$toolbar->method('getMyViews')->willReturn($test['myviews']);
		$toolbar->method('appendLink')
			->willReturnCallback(function ($name, $link, $active) use (&$checker) {
				$checker[] = [$name, $link, $active];
			});

		$toolbar->renderSubmenu();

		$this->assertEquals($check['links'], $checker, sprintf($msg, 'Failed to create the links'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::getRenderFrontendButtons
	 */
	public function testGetRenderFrontendButtons()
	{
		$toolbar = new ToolbarStub(static::$container);

		ReflectionHelper::setValue($toolbar, 'renderFrontendButtons', true);

		$this->assertTrue($toolbar->getRenderFrontendButtons());
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::setRenderFrontendButtons
	 */
	public function testSetRenderFrontendButtons()
	{
		$toolbar = new ToolbarStub(static::$container);

		$toolbar->setRenderFrontendButtons(true);

		$this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendButtons'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::getRenderFrontendSubmenu
	 */
	public function testGetRenderFrontendSubmenu()
	{
		$toolbar = new ToolbarStub(static::$container);

		ReflectionHelper::setValue($toolbar, 'renderFrontendSubmenu', true);

		$this->assertTrue($toolbar->getRenderFrontendSubmenu());
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::setRenderFrontendSubmenu
	 */
	public function testSetRenderFrontendSubmenu()
	{
		$toolbar = new ToolbarStub(static::$container);

		$toolbar->setRenderFrontendSubmenu(true);

		$this->assertTrue(ReflectionHelper::getValue($toolbar, 'renderFrontendSubmenu'));
	}

	/**
	 * @group           Toolbar
	 * @covers          FOF40\Toolbar\Toolbar::isDataView
	 * @dataProvider    ToolbarDataprovider::getTestIsDataView
	 */
	public function testIsDataView($test, $check)
	{
		$msg = 'Toolbar::isDataView %s - Case: ' . $check['case'];

		$platform            = static::$container->platform;
		$platform::$template = 'fake_test_template';
		$platform::$uriBase  = 'www.example.com';

		$TestContainer = static::$container;

		$controller = $this->getMockBuilder('\FOF40\Tests\Stubs\Controller\ControllerStub')
			->setMethods(['getView'])
			->setConstructorArgs([static::$container])
			->getMock();

		$controller->method('getView')->willReturnCallback(function () use ($test, $TestContainer) {
			if (!is_null($test['mock']['getView']))
			{
				return new $test['mock']['getView']($TestContainer);
			}

			return null;
		});

		$dispacher = $this->getMockBuilder('FOF40\Dispatcher\Dispatcher')
			->setMethods(['getController'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispacher->method('getController')
			->willReturn($test['mock']['getController'] ? $controller : null);

		$container = new TestContainer([
			'dispatcher' => $dispacher,
		]);

		$toolbar = new ToolbarStub($container);

		ReflectionHelper::setValue($toolbar, 'isDataView', $test['mock']['cache']);

		$result = $toolbar->isDataView();

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
	}
}
