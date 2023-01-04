<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;


use FOF40\Tests\Helpers\FOFTestCase;

class MockApplicationWeb extends MockApplicationBase
{
	/**
	 * Mock storage for the response body.
	 *
	 * @var    array
	 * @since  12.2
	 */
	public static $body = [];

	/**
	 * Mock storage for the response headers.
	 *
	 * @var    array
	 * @since  3.2
	 */
	public static $headers = [];

	/**
	 * Mock storage for the response cache status.
	 *
	 * @var    boolean
	 * @since  3.2
	 */
	public static $cachable = false;

	/**
	 * Gets the methods of the WebApplication object.
	 *
	 * @return  array
	 *
	 * @since   3.4
	 */
	public static function getMethods()
	{
		// Collect all the relevant methods in WebApplication (work in progress).
		$methods = [
			'allowCache',
			'appendBody',
			'clearHeaders',
			'execute',
			'get',
			'getBody',
			'getDocument',
			'getHeaders',
			'getLanguage',
			'getSession',
			'loadConfiguration',
			'loadDocument',
			'loadLanguage',
			'loadSession',
			'prependBody',
			'redirect',
			'sendHeaders',
			'set',
			'setBody',
			'setHeader',
		];

		return array_merge($methods, parent::getMethods());
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
		// Mock calls to WebApplication::getDocument().
		$mockObject->expects($test->any())->method('getDocument')->willReturn(MockDocument::create($test));

		// Mock calls to WebApplication::getLanguage().
		$mockObject->expects($test->any())->method('getLanguage')->willReturn(MockLanguage::create($test));

		// Mock a call to WebApplication::getSession().
		if (isset($options['session']))
		{
			$mockObject->expects($test->any())->method('getSession')->willReturn($options['session']);
		}
		else
		{
			$mockObject->expects($test->any())->method('getSession')->willReturn(MockSession::create($test));
		}

		$test->assignMockCallbacks(
			$mockObject,
			[
				'appendBody'   => [
					(is_callable([$test, 'mockAppendBody']) ? $test : get_called_class()), 'mockAppendBody',
				],
				'getBody'      => [(is_callable([$test, 'mockGetBody']) ? $test : get_called_class()), 'mockGetBody'],
				'prependBody'  => [
					(is_callable([$test, 'mockPrependBody']) ? $test : get_called_class()), 'mockPrependBody',
				],
				'setBody'      => [(is_callable([$test, 'mockSetBody']) ? $test : get_called_class()), 'mockSetBody'],
				'getHeaders'   => [
					(is_callable([$test, 'mockGetHeaders']) ? $test : get_called_class()), 'mockGetHeaders',
				],
				'setHeader'    => [
					(is_callable([$test, 'mockSetHeader']) ? $test : get_called_class()), 'mockSetHeader',
				],
				'clearHeaders' => [
					(is_callable([$test, 'mockClearHeaders']) ? $test : get_called_class()), 'mockClearHeaders',
				],
				'allowCache'   => [
					(is_callable([$test, 'mockAllowCache']) ? $test : get_called_class()), 'mockAllowCache',
				],
			]
		);

		// Reset the body storage.
		static::$body = [];

		// Reset the headers storage.
		static::$headers = [];

		// Reset the cache storage.
		static::$cachable = false;

		return parent::addBehaviours($test, $mockObject, $options);
	}

	/**
	 * Creates and instance of the mock WebApplication object.
	 *
	 * The test can implement the following overrides:
	 * - mockAppendBody
	 * - mockGetBody
	 * - mockPrepentBody
	 * - mockSetBody
	 * - mockGetHeaders
	 * - mockSetHeaders
	 * - mockAllowCache
	 *
	 * If any *Body methods are implemented in the test class, all should be implemented otherwise behaviour will be unreliable.
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

		// Collect all the relevant methods in WebApplication (work in progress).
		$methods = self::getMethods();

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Application\WebApplication')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->getMock();

		$mockObject = self::addBehaviours($test, $mockObject, $options);

		return $mockObject;
	}

	/**
	 * Mock WebApplication->appendBody method.
	 *
	 * @param   string $content The content to append to the response body.
	 *
	 * @return  mixed
	 *
	 * @since   12.2
	 */
	public static function mockAppendBody($content)
	{
		array_push(static::$body, (string) $content);
	}

	/**
	 * Mock WebApplication->getBody method.
	 *
	 * @param   boolean $asArray True to return the body as an array of strings.
	 *
	 * @return  mixed
	 *
	 * @since   12.2
	 */
	public static function mockGetBody($asArray = false)
	{
		return $asArray ? static::$body : implode((array) static::$body);
	}

	/**
	 * Mock WebApplication->appendBody method.
	 *
	 * @param   string $content The content to append to the response body.
	 *
	 * @return  mixed
	 *
	 * @since   12.2
	 */
	public static function mockPrependBody($content)
	{
		array_unshift(static::$body, (string) $content);
	}

	/**
	 * Mock WebApplication->setBody method.
	 *
	 * @param   string $content The body of the response.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	public static function mockSetBody($content)
	{
		static::$body = [$content];
	}

	/**
	 * Mock WebApplication->getHeaders method.
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public static function mockGetHeaders()
	{
		return static::$headers;
	}

	/**
	 * Mock WebApplication->setHeader method.
	 *
	 * @param   string  $name    The name of the header to set.
	 * @param   string  $value   The value of the header to set.
	 * @param   boolean $replace True to replace any headers with the same name.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function mockSetHeader($name, $value, $replace = false)
	{
		// Sanitize the input values.
		$name  = (string) $name;
		$value = (string) $value;

		// If the replace flag is set, unset all known headers with the given name.
		if ($replace)
		{
			foreach (static::$headers as $key => $header)
			{
				if ($name == $header['name'])
				{
					unset(static::$headers[$key]);
				}
			}

			// Clean up the array as unsetting nested arrays leaves some junk.
			static::$headers = array_values(static::$headers);
		}

		// Add the header to the internal array.
		static::$headers[] = ['name' => $name, 'value' => $value];
	}

	/**
	 * Mock WebApplication->clearHeaders method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public static function mockClearHeaders()
	{
		static::$headers = [];
	}

	/**
	 * Mock WebApplication->allowCache method.
	 *
	 * @param   boolean $allow True to allow browser caching.
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	public static function mockAllowCache($allow = null)
	{
		if ($allow !== null)
		{
			static::$cachable = (bool) $allow;
		}

		return static::$cachable;
	}
}
