<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Factory\Magic;

use FOF40\Factory\Magic\DispatcherFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestContainer;

/**
 * @covers      FOF40\Factory\Magic\DispatcherFactory::<protected>
 * @covers      FOF40\Factory\Magic\DispatcherFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class DispatcherFactoryTest extends FOFTestCase
{
	/**
	 * @covers          FOF40\Factory\Magic\DispatcherFactory::make
	 * @dataProvider    getTestMake
	 */
	public function testMake($test, $check)
	{
		$msg = 'DispatcherFactory::make %s - Case: ' . $check['case'];

		$config['componentName'] = $test['component'];

		if ($test['backend_path'])
		{
			$config['backEndPath'] = $test['backend_path'];
		}

		$container = new TestContainer($config);

		// Required so we force FOF to read the fof.xml file
		$dummy = $container->appConfig;

		$factory = new DispatcherFactory($container);

		$result = $factory->make([]);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
	}

	public function getTestMake()
	{
		$data[] = [
			[
				'component'    => 'com_fakeapp',
				'backend_path' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
			],
			[
				'case'   => 'DefaultDispatcher exists',
				'result' => 'Fakeapp\Site\Dispatcher\DefaultDispatcher',
			],
		];

		$data[] = [
			[
				'component'    => 'com_dummyapp',
				'backend_path' => JPATH_TESTS . '/Stubs/Dummyapp/Admin',
			],
			[
				'case'   => 'DefaultDispatcher does not exist',
				'result' => 'FOF40\\Dispatcher\\Dispatcher',
			],
		];

		return $data;
	}
}
