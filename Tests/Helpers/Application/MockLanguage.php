<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;

use FOF40\Tests\Helpers\FOFTestCase;

class MockLanguage
{
	public static $loadedLanguages = [];

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
		$methods = static::getMethods();

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Language\Language')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		// Mock selected methods.
		$test->assignMockReturns(
			$mockObject, [
				'getInstance' => $mockObject,
				'getTag'      => 'en-GB',
				// An additional 'test' method for confirming this object is successfully mocked.
				'test'        => 'ok',
			]
		);

		$test->assignMockCallbacks(
			$mockObject,
			[
				'_'    => [get_called_class(), 'mock_'],
				'load' => [get_called_class(), 'mockload'],
			]
		);

		return $mockObject;
	}

	public static function getMethods()
	{
		return [
			'_',
			'getInstance',
			'getTag',
			'test',
			'load',
		];
	}

	/**
	 * Callback for the mock Language::_ method.
	 *
	 * @param   string  $string               The string to translate
	 * @param   boolean $jsSafe               Make the result javascript safe
	 * @param   boolean $interpretBackSlashes Interpret \t and \n
	 *
	 * @return string
	 *
	 * @since  11.3
	 */
	public function mock_($string, $jsSafe = false, $interpretBackSlashes = true)
	{
		return $string;
	}

	public static function mockload($extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false, $default = true)
	{
		static::$loadedLanguages[] = [$extension, $basePath, $lang, $reload, $default];
	}
}
