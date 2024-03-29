<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Configuration\Domain;

use FOF40\Configuration\Domain\Container;
use FOF40\Tests\Helpers\FOFTestCase;

/**
 * @covers  \FOF40\Configuration\Domain\Container::<protected>
 * @covers  \FOF40\Configuration\Domain\Container::<private>
 */
class ContainerTest extends FOFTestCase
{
	/**
	 * @group       ConfigurationContainer
	 * @covers      \FOF40\Configuration\Domain\Container::parseDomain
	 */
	public function testParseDomain()
	{
		$auth = new Container();

		$ret = [];

		$file = __DIR__ . '/../../_data/configuration/container.xml';
		$xml  = simplexml_load_file($file);

		$auth->parseDomain($xml, $ret);

		$this->assertArrayHasKey('container', $ret, 'The authentication key must be set');
		$this->assertArrayHasKey('some', $ret['container'], 'All options must be read');
		$this->assertEquals('thing', $ret['container']['some'], 'Option values must be read');
		$this->assertArrayHasKey('foo', $ret['container'], 'All options must be read');
		$this->assertEquals('bar', $ret['container']['foo'], 'Option values must be read');
		$this->assertArrayNotHasKey('nope', $ret['container'], 'Non-options must NOT be read');
	}

	/**
	 * @covers       \FOF40\Configuration\Domain\Container::get
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
		$auth = new Container();
		$ret  = [];

		$file = __DIR__ . '/../../_data/configuration/container.xml';
		$xml  = simplexml_load_file($file);

		$auth->parseDomain($xml, $ret);

		$actual = $auth->get($ret, $key, $default);

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
