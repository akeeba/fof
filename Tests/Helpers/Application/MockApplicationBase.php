<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;

use FOF40\Tests\Helpers\FOFTestCase;

class MockApplicationBase
{
	/**
	 * Gets the methods of the BaseApplication object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		return [
			'close',
			'getIdentity',
			'registerEvent',
			'triggerEvent',
			'loadDispatcher',
			'loadIdentity',
		];
	}

	/**
	 * Adds mock objects for some methods.
	 *
	 * @param  FOFTestCase                              $test       A test object.
	 * @param  \PHPUnit_Framework_MockObject_MockObject $mockObject The mock object.
	 * @param  array                                    $options    A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject  The object with the behaviours added
	 *
	 * @since   3.4
	 */
	public static function addBehaviours($test, $mockObject, $options)
	{
		$test->assignMockReturns(
			$mockObject,
			['close' => true]
		);

		return $mockObject;
	}

	/**
	 * Creates and instance of the mock BaseApplication object.
	 *
	 * @param   FOFTestCase $test    A test object.
	 * @param   array       $options A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   11.3
	 */
	public static function create($test, $options = [])
	{
		// Set expected server variables.
		if (!isset($_SERVER['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = 'localhost';
		}

		// Collect all the relevant methods in BaseApplication.
		$methods = self::getMethods();

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Application\BaseApplication')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->getMock();

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}
