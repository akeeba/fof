<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Factory;

use FOF40\Factory\MagicFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestContainer;

require_once 'MagicFactoryDataprovider.php';

/**
 * @covers      FOF40\Factory\MagicFactory::<protected>
 * @covers      FOF40\Factory\MagicFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class MagicFactoryTest extends FOFTestCase
{
	/**
	 * @group           MagicFactory
	 * @covers          FOF40\Factory\MagicFactory::controller
	 * @dataProvider    MagicFactoryDataprovider::getTestController
	 */
	public function testController($test, $check)
	{
		$msg = 'MagicFactory::controller %s - Case: ' . $check['case'];

		$factory = new MagicFactory(static::$container);

		$result = $factory->controller($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           MagicFactory
	 * @covers          FOF40\Factory\MagicFactory::model
	 * @dataProvider    MagicFactoryDataprovider::getTestModel
	 */
	public function testModel($test, $check)
	{
		$msg = 'MagicFactory::model %s - Case: ' . $check['case'];

		$factory = new MagicFactory(static::$container);

		$result = $factory->model($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           MagicFactory
	 * @covers          FOF40\Factory\MagicFactory::view
	 * @dataProvider    MagicFactoryDataprovider::getTestView
	 */
	public function testView($test, $check)
	{
		$msg = 'MagicFactory::view %s - Case: ' . $check['case'];

		$platform            = static::$container->platform;
		$platform::$template = 'fake_test_template';
		$platform::$uriBase  = 'www.example.com';

		$factory = new MagicFactory(static::$container);

		$result = $factory->view($test['view']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           MagicFactory
	 * @covers          FOF40\Factory\MagicFactory::dispatcher
	 * @dataProvider    MagicFactoryDataprovider::getTestDispatcher
	 */
	public function testDispatcher($test, $check)
	{
		$msg = 'MagicFactory::dispatcher %s - Case: ' . $check['case'];

		$config = [
			'backEndPath' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
		];

		if (!$test['backend'])
		{
			$config['componentNamespace'] = 'WhateverMan';
		}

		$container = new TestContainer($config);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		// Required so we force FOF to read the fof.xml file
		$dummy = $container->appConfig;

		$factory = new MagicFactory($container);

		$result = $factory->dispatcher();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           MagicFactory
	 * @covers          FOF40\Factory\MagicFactory::transparentAuthentication
	 * @dataProvider    MagicFactoryDataprovider::getTestTransparentAuthentication
	 */
	public function testTransparentAuthentication($test, $check)
	{
		$msg = 'MagicFactory::transparentAuthentication %s - Case: ' . $check['case'];

		$config = [
			'backEndPath' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
		];

		if (!$test['backend'])
		{
			$config['componentNamespace'] = 'WhateverMan';
		}

		$container = new TestContainer($config);

		$platform           = $container->platform;
		$platform::$isAdmin = $test['backend'];

		// Required so we force FOF to read the fof.xml file
		$dummy = $container->appConfig;

		$factory = new MagicFactory($container);

		$result = $factory->transparentAuthentication();

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}
}
