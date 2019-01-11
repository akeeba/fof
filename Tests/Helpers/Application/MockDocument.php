<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\Helpers\Application;

use FOF40\Tests\Helpers\FOFTestCase;

class MockDocument
{
	/**
	 * Creates and instance of the mock JLanguage object.
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
		$mockObject = $test->getMockBuilder('\JDocument')
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
