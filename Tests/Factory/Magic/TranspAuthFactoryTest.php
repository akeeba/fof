<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Factory\Magic;

use FOF40\Factory\Magic\TransparentAuthenticationFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestContainer;

/**
 * @covers      FOF40\Factory\Magic\TransparentAuthenticationFactory::<protected>
 * @covers      FOF40\Factory\Magic\TransparentAuthenticationFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class TransparentAuthenticationFactoryTest extends FOFTestCase
{
	/**
	 * @covers          FOF40\Factory\Magic\TransparentAuthenticationFactory::make
	 * @dataProvider    getTestMake
	 */
	public function testMake($test, $check)
	{
		$msg = 'TransparentAuthenticationFactory::make %s - Case: ' . $check['case'];

		$config['componentName'] = $test['component'];

		if ($test['backend_path'])
		{
			$config['backEndPath'] = $test['backend_path'];
		}

		$container = new TestContainer($config);

		// Required so we force FOF to read the fof.xml file
		$dummy = $container->appConfig;

		$factory = new TransparentAuthenticationFactory($container);

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
				'case'   => 'TransparentAuthenticationFactory exists',
				'result' => 'Fakeapp\Site\TransparentAuthentication\DefaultTransparentAuthentication',
			],
		];

		$data[] = [
			[
				'component'    => 'com_dummyapp',
				'backend_path' => JPATH_TESTS . '/Stubs/Dummyapp/Admin',
			],
			[
				'case'   => 'TransparentAuthenticationFactory does not exist',
				'result' => 'FOF40\\TransparentAuthentication\\TransparentAuthentication',
			],
		];

		return $data;
	}
}
