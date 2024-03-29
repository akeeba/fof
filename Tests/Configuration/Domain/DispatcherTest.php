<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Configuration\Domain;

use FOF40\Configuration\Domain\Dispatcher;
use FOF40\Tests\Helpers\FOFTestCase;

/**
 * @covers  FOF40\Configuration\Domain\Dispatcher::<protected>
 * @covers  FOF40\Configuration\Domain\Dispatcher::<private>
 */
class DispatcherTest extends FOFTestCase
{
	/** @var   Dispatcher  The object to test */
	protected $object = null;

	/** @var   array  The data returned from parsing the XML file, used to test fetching data */
	protected $data = [];

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new Dispatcher();

		$file = __DIR__ . '/../../_data/configuration/dispatcher.xml';
		$xml  = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);
	}

	/**
	 * @covers  FOF40\Configuration\Domain\Dispatcher::parseDomain
	 *
	 * @return  void
	 */
	public function testParseDomain()
	{
		$this->data = [];

		$file = __DIR__ . '/../../_data/configuration/dispatcher.xml';
		$xml  = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);

		$this->assertArrayHasKey('dispatcher', $this->data, 'The dispatcher key must be set');
		$this->assertArrayHasKey('some', $this->data['dispatcher'], 'All options must be read');
		$this->assertEquals('thing', $this->data['dispatcher']['some'], 'Option values must be read');
		$this->assertArrayHasKey('foo', $this->data['dispatcher'], 'All options must be read');
		$this->assertEquals('bar', $this->data['dispatcher']['foo'], 'Option values must be read');
		$this->assertArrayNotHasKey('nope', $this->data['dispatcher'], 'Non-options must NOT be read');
	}

	/**
	 * @covers       FOF40\Configuration\Domain\Dispatcher::get
	 *
	 * @dataProvider getTestGet
	 *
	 * @param   string $key      Key to read
	 * @param   mixed  $default  Default value
	 * @param   mixed  $expected Expected value
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGet($key, $default, $expected, $message)
	{
		$actual = $this->object->get($this->data, $key, $default);
		$this->assertEquals($expected, $actual, $message);
	}

	public function getTestGet()
	{
		return [
			['some', 'NOPE', 'thing', 'Existing option must be read correctly'],
			['foo', 'NOPE', 'bar', 'Existing option must be read correctly'],
			['godzilla', 'narf', 'narf', 'Non-existing option must return default value'],
			['*', '', ['some' => 'thing', 'foo' => 'bar'], 'Retrieving all the options'],
		];
	}
}
