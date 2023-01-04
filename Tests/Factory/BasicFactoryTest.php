<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Tests\Factory;

use FOF40\Factory\BasicFactory;
use FOF40\Factory\Exception\ControllerNotFound;
use FOF40\Factory\Exception\ModelNotFound;
use FOF40\Factory\Exception\ViewNotFound;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;

require_once 'BasicFactoryDataprovider.php';

/**
 * @covers      FOF40\Factory\BasicFactory::<protected>
 * @covers      FOF40\Factory\BasicFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class BasicFactoryTest extends FOFTestCase
{
	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::controller
	 * @dataProvider    BasicFactoryDataprovider::getTestController
	 */
	public function testController($test, $check)
	{
		$msg = 'BasicFactory::controller %s - Case: ' . $check['case'];

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new BasicFactory(static::$container);

		if (!$check['result'])
		{
			$this->expectException(ControllerNotFound::class);
		}

		$result = $factory->controller($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::model
	 * @dataProvider    BasicFactoryDataprovider::getTestModel
	 */
	public function testModel($test, $check)
	{
		$msg = 'BasicFactory::model %s - Case: ' . $check['case'];

		$platform           = static::$container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new BasicFactory(static::$container);

		if (!$check['result'])
		{
			$this->expectException(ModelNotFound::class);
		}

		$result = $factory->model($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::view
	 * @dataProvider    BasicFactoryDataprovider::getTestView
	 */
	public function testView($test, $check)
	{
		$msg                 = 'BasicFactory::view %s - Case: ' . $check['case'];
		$platform            = static::$container->platform;
		$platform::$template = 'fake_test_template';
		$platform::$uriBase  = 'www.example.com';
		$platform::$isAdmin  = $test['backend'];

		$factory = new BasicFactory(static::$container);

		if (!$check['result'])
		{
			$this->expectException(ViewNotFound::class);
		}

		$result = $factory->view($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::dispatcher
	 * @dataProvider    BasicFactoryDataprovider::getTestDispatcher
	 */
	public function testDispatcher($test, $check)
	{
		$msg = 'BasicFactory::dispatcher %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new BasicFactory($container);

		$result = $factory->dispatcher();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::toolbar
	 * @dataProvider    BasicFactoryDataprovider::getTestToolbar
	 */
	public function testToolbar($test, $check)
	{
		$msg = 'BasicFactory::toolbar %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new BasicFactory($container);

		$result = $factory->toolbar();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           BasicFactory
	 * @covers          FOF40\Factory\BasicFactory::transparentAuthentication
	 * @dataProvider    BasicFactoryDataprovider::getTestTransparentAuthentication
	 */
	public function testTransparentAuthentication($test, $check)
	{
		$msg = 'BasicFactory::transparentAuthentication %s - Case: ' . $check['case'];

		$container = new TestContainer([
			'componentName' => $test['component'],
		]);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		$factory = new BasicFactory($container);

		$result = $factory->transparentAuthentication();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}
}
