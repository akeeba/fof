<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;


use FOF40\Tests\Helpers\FOFTestCase;

class MockApplicationCms extends MockApplicationWeb
{
	/**
	 * Gets the methods of the CMSApplication object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		// Collect all the relevant methods in CMSApplication (work in progress).
		$methods = [
			'getMenu',
			'getPathway',
			'getTemplate',
			'getLanguageFilter',
			'initialiseApp',
			'isAdmin',
			'isSite',
			'getUserState',
			'getUserStateFromRequest',
		];

		return array_merge($methods, parent::getMethods());
	}

	/**
	 * Adds mock objects for some methods.
	 *
	 * @param   FOFTestCase                              $test       A test object.
	 * @param   \PHPUnit_Framework_MockObject_MockObject $mockObject The mock object.
	 * @param   array                                    $options    A set of options to configure the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject  The object with the behaviours added
	 *
	 * @since   3.4
	 */
	public static function addBehaviours($test, $mockObject, $options)
	{
		// Mock calls to CMSApplication::getMenu();
		$mockObject->expects($test->any())->method('getMenu')->will($test->returnValue(MockMenu::create($test)));

		return parent::addBehaviours($test, $mockObject, $options);
	}

	/**
	 * Creates and instance of the mock CMSApplication object.
	 *
	 * The test can implement the following overrides:
	 * - mockAppendBody
	 * - mockGetBody
	 * - mockPrepentBody
	 * - mockSetBody
	 *
	 * If any *Body methods are implemented in the test class, all should be implemented otherwise behaviour will be unreliable.
	 *
	 * @param   FOFTestCase $test        A test object.
	 * @param   array       $options     A set of options to configure the mock.
	 * @param   array       $constructor An array containing constructor arguments to inject into the mock.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   3.2
	 */
	public static function create($test, $options = [], $constructor = [])
	{
		// Set expected server variables.
		if (!isset($_SERVER['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = 'localhost';
		}

		$methods = self::getMethods();

		if (isset($options))
			// Create the mock.
		{
			$mockObject = $test->getMockBuilder('\Joomla\CMS\Application\CMSApplication')
				->setMethods($methods)
				->setConstructorArgs($constructor)
				->setMockClassName('')
				->getMock();
		}

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}
}
