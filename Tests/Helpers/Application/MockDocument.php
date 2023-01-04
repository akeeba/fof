<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;

use FOF40\Tests\Helpers\FOFTestCase;

class MockDocument
{
	/**
	 * Creates and instance of the mock Language object.
	 *
	 * @param   FOFTestCase $test A test object.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   11.3
	 */
	public static function create($test)
	{
		// Collect all the relevant methods in JDatabase.
		$methods = [
			'parse',
			'render',
			'test',
		];

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Document\Document')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		// Mock selected methods.
		$test->assignMockReturns(
			$mockObject, [
				'parse' => $mockObject,
				// An additional 'test' method for confirming this object is successfully mocked.
				'test'  => 'ok',
			]
		);

		return $mockObject;
	}
}
