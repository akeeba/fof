<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;


use FOF40\Tests\Helpers\FOFTestCase;

class MockApplicationCli extends MockApplicationBase
{
	/**
	 * Gets the methods of the CliApplication object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		// Collect all the relevant methods in CliApplication.
		$methods = [
			'get',
			'execute',
			'loadConfiguration',
			'out',
			'in',
			'set',
		];

		return array_merge($methods, parent::getMethods());
	}

	/**
	 * Creates and instance of the mock CliApplication object.
	 *
	 * @param   FOFTestCase $test    A test object.
	 * @param   array       $options A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   12.2
	 */
	public static function create($test, $options = [])
	{
		// Collect all the relevant methods in CliApplication.
		$methods = self::getMethods();

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Application\CliApplication')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->getMock();

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}
