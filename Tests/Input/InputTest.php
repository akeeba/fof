<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Input;

use FOF40\Input\Input;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

/**
 * @covers  FOF40\Input\Input::<protected>
 * @covers  FOF40\Input\Input::<private>
 */
class InputTest extends FOFTestCase
{
	/**
	 * @covers        FOF40\Input\Input::__construct
	 *
	 * @dataProvider  FOF40\Tests\Input\InputProvider::getTestConstructor
	 *
	 * @backupGlobals enabled
	 */
	public function testConstructor($source, $superGlobals, $match, $message)
	{
		// Initialise superglobals for this test
		$_GET     = isset($superGlobals['get']) ? $superGlobals['get'] : [];
		$_POST    = isset($superGlobals['post']) ? $superGlobals['post'] : [];
		$_FILES   = isset($superGlobals['files']) ? $superGlobals['files'] : [];
		$_COOKIE  = isset($superGlobals['cookie']) ? $superGlobals['cookie'] : [];
		$_ENV     = isset($superGlobals['env']) ? $superGlobals['env'] : [];
		$_SERVER  = isset($superGlobals['server']) ? $superGlobals['server'] : [];
		$_REQUEST = isset($superGlobals['request']) ? $superGlobals['request'] : [];

		$input = new Input($source);
		$data  = ReflectionHelper::getValue($input, 'data');

		$this->assertInternalType('array', $data, $message);

		foreach ($match as $k => $v)
		{
			$this->assertArrayHasKey($k, $data, $message);
			$this->assertEquals($v, $data[$k], $message);
		}
	}

	/**
	 * @covers       FOF40\Input\Input::get
	 * @covers       FOF40\Input\Input::_cleanVar
	 *
	 * @dataProvider FOF40\Tests\Input\InputProvider::getTestGet
	 */
	public function testGet($key, $filter, $expected, $message)
	{
		$input  = new Input(InputProvider::getSampleInputData());
		$actual = $input->get($key, null, $filter);

		$delta = 0.0;

		if (in_array($filter, ['float', 'double']))
		{
			$delta = 0.000001;
		}

		$this->assertEquals($expected, $actual, $message, $delta);
	}

	/**
	 * @covers       FOF40\Input\Input::getData
	 */
	public function testGetData()
	{
		$input = new Input(InputProvider::getSampleInputData());

		$data = $input->getData();

		$this->assertInternalType('array', $data, 'getData must return an array');
		$this->assertEquals(InputProvider::getSampleInputData(), $data, 'getData must return the exact input data');
	}

	/**
	 * @covers       FOF40\Input\Input::__call
	 *
	 * @dataProvider FOF40\Tests\Input\InputProvider::getTestMagicCall
	 */
	public function testMagicCall($key, $filter, $expected, $message)
	{
		$method = 'get' . ucfirst($filter);

		$input  = new Input(InputProvider::getSampleInputData());
		$actual = $input->$method($key, null);

		$delta = 0.0;

		if (in_array($filter, ['float', 'double']))
		{
			$delta = 0.000001;
		}

		$this->assertEquals($expected, $actual, $message, $delta);
	}
}
