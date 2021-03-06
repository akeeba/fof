<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace FOF30\Tests\Helpers\Application;


use FOF30\Tests\Helpers\FOFTestCase;

class MockApplicationCli extends MockApplicationBase
{
	/**
	 * Gets the methods of the JApplicationCli object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		// Collect all the relevant methods in JApplicationCli.
		$methods = array(
			'get',
			'execute',
			'loadConfiguration',
			'out',
			'in',
			'set',
		);

		return array_merge($methods, parent::getMethods());
	}

	/**
	 * Creates and instance of the mock JApplicationCli object.
	 *
	 * @param   FOFTestCase  $test     A test object.
	 * @param   array     $options  A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   12.2
	 */
	public static function create($test, $options = array())
	{
		// Collect all the relevant methods in JApplicationCli.
		$methods = self::getMethods();

		// Create the mock.
		$mockObject = $test->getMockBuilder('\JApplicationCli')
			->setMethods($methods)
			->setConstructorArgs(array())
			->setMockClassName('')
			->getMock();

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}
