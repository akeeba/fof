<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Factory;

use FOF40\Factory\SwitchFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;

require_once 'SwitchFactoryDataprovider.php';

/**
 * @covers      FOF40\Factory\SwitchFactory::<protected>
 * @covers      FOF40\Factory\SwitchFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class SwitchFactoryTest extends FOFTestCase
{
	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::controller
	 * @dataProvider    SwitchFactoryDataprovider::getTestController
	 */
	public function testController($test, $check)
	{
		$msg = 'SwitchFactory::controller %s - Case: ' . $check['case'];

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new SwitchFactory(static::$container);

		$result = $factory->controller($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::model
	 * @dataProvider    SwitchFactoryDataprovider::getTestModel
	 */
	public function testModel($test, $check)
	{
		$msg = 'SwitchFactory::model %s - Case: ' . $check['case'];

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new SwitchFactory(static::$container);

		$result = $factory->model($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::view
	 * @dataProvider    SwitchFactoryDataprovider::getTestView
	 */
	public function testView($test, $check)
	{
		$msg                 = 'SwitchFactory::view %s - Case: ' . $check['case'];
		$platform            = static::$container->platform;
		$platform::$template = 'fake_test_template';
		$platform::$uriBase  = 'www.example.com';
		$platform::$isAdmin  = $test['backend'];

		$factory = new SwitchFactory(static::$container);

		$result = $factory->view($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::dispatcher
	 * @dataProvider    SwitchFactoryDataprovider::getTestDispatcher
	 */
	public function testDispatcher($test, $check)
	{
		$msg = 'SwitchFactory::dispatcher %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new SwitchFactory($container);

		$result = $factory->dispatcher();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::toolbar
	 * @dataProvider    SwitchFactoryDataprovider::getTestToolbar
	 */
	public function testToolbar($test, $check)
	{
		$msg = 'SwitchFactory::toolbar %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new SwitchFactory($container);

		$result = $factory->toolbar();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           SwitchFactory
	 * @covers          FOF40\Factory\SwitchFactory::transparentAuthentication
	 * @dataProvider    SwitchFactoryDataprovider::getTestTransparentAuthentication
	 */
	public function testTransparentAuthentication($test, $check)
	{
		$msg = 'SwitchFactory::transparentAuthentication %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new SwitchFactory($container);

		$result = $factory->transparentAuthentication();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}
}
