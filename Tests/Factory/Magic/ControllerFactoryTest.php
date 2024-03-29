<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Factory\Magic;

use FOF40\Factory\Magic\ControllerFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;

require_once 'ControllerFactoryDataprovider.php';

/**
 * @covers      FOF40\Factory\Magic\ControllerFactory::<protected>
 * @covers      FOF40\Factory\Magic\ControllerFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class ControllerFactoryTest extends FOFTestCase
{
	/**
	 * @covers          FOF40\Factory\Magic\ControllerFactory::make
	 * @dataProvider    ControllerFactoryDataprovider::getTestMake
	 */
	public function testMake($test, $check)
	{
		$msg = 'ControllerFactory::make %s - Case: ' . $check['case'];

		$config['componentName'] = $test['component'];

		if ($test['backend_path'])
		{
			$config['backEndPath'] = $test['backend_path'];
		}

		$container = new TestContainer($config);

		// Required so we force FOF to read the fof.xml file
		$dummy = $container->appConfig;

		$factory = new ControllerFactory($container);

		if ($check['exception'])
		{
			$this->setExpectedException('FOF40\Factory\Exception\ControllerNotFound');
		}

		$result = $factory->make($test['name'], $test['config']);

		$this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
		$this->assertEquals($check['autoRouting'], ReflectionHelper::getValue($result, 'autoRouting'), sprintf($msg, 'Failed to set the autorouting'));
		$this->assertEquals($check['csrf'], ReflectionHelper::getValue($result, 'csrfProtection'), sprintf($msg, 'Failed to set the csrfProtection'));
		$this->assertEquals($check['view'], ReflectionHelper::getValue($result, 'viewName'), sprintf($msg, 'Failed to set the viewName'));
		$this->assertEquals($check['model'], ReflectionHelper::getValue($result, 'modelName'), sprintf($msg, 'Failed to set the modelName'));
		$this->assertEquals($check['priv'], ReflectionHelper::getValue($result, 'taskPrivileges'), sprintf($msg, 'Failed to set the taskPrivileges'));
		$this->assertEquals($check['cache'], ReflectionHelper::getValue($result, 'cacheableTasks'), sprintf($msg, 'Failed to set the cacheableTasks'));
		$this->assertEquals($check['taskMap'], ReflectionHelper::getValue($result, 'taskMap'), sprintf($msg, 'Failed to set the taskMap'));
	}
}
