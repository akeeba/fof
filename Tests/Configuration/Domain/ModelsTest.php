<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Configuration\Domain;

use FOF40\Configuration\Domain\Models;
use FOF40\Tests\Helpers\FOFTestCase;

/**
 * @covers  FOF40\Configuration\Domain\Models::<protected>
 * @covers  FOF40\Configuration\Domain\Models::<private>
 */
class ModelsTest extends FOFTestCase
{
	/** @var   Models  The object to test */
	protected $object = null;

	/** @var   array  The data returned from parsing the XML file, used to test fetching data */
	protected $data = [];

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new Models();

		$file = __DIR__ . '/../../_data/configuration/models.xml';
		$xml  = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);
	}

	/**
	 * @covers  FOF40\Configuration\Domain\Models::parseDomain
	 *
	 * @return  void
	 */
	public function testParseDomain()
	{
		$this->data = [];

		$file = __DIR__ . '/../../_data/configuration/models.xml';
		$xml  = simplexml_load_file($file);

		$this->object->parseDomain($xml, $this->data);

		$this->assertArrayHasKey('models', $this->data, 'The models key must be set');
		$this->assertArrayHasKey('*', $this->data['models'], 'All model keys must be read');
		$this->assertArrayHasKey('Orders', $this->data['models'], 'All model keys must be read');
		$this->assertArrayHasKey('Users', $this->data['models'], 'All model keys must be read');
		$this->assertArrayHasKey('behaviors', $this->data['models']['*'], 'Model keys must have behaviors subkey');
		$this->assertArrayHasKey('behaviorsMerge', $this->data['models']['*'], 'Model keys must have behaviorsMerge subkey');
		$this->assertArrayHasKey('tablealias', $this->data['models']['*'], 'Model keys must have tablealias subkey');
		$this->assertArrayHasKey('fields', $this->data['models']['*'], 'Model keys must have fields subkey');
		$this->assertArrayHasKey('relations', $this->data['models']['*'], 'Model keys must have relations subkey');
	}

	/**
	 * @covers       FOF40\Configuration\Domain\Models::get
	 * @covers       FOF40\Configuration\Domain\Models::getField
	 *
	 * @dataProvider getTestGetField
	 *
	 * @param   string $key      Key to read
	 * @param   mixed  $default  Default value
	 * @param   mixed  $expected Expected value
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGetField($key, $default, $expected, $message)
	{
		$actual = $this->object->get($this->data, $key, $default);
		$this->assertEquals($expected, $actual, $message);
	}

	public function getTestGetField()
	{
		return [
			['*.field.locked_by', 'NOPE', 'checked_out', 'Star model must be read'],
			['Orders.field.locked_by', 'NOPE', 'checked_out', 'Star model options must be applied to other models'],
			['Orders.field.enabled', 'NOPE', 'published', 'Custom model fields must be applied'],
			['Users.field.locked_by', 'NOPE', 'locked', 'Custom model fields must override star model'],
			['Users.field.bazinga', 'bork', 'bork', 'Undefined override must return its default value'],
		];
	}

	/**
	 * @covers       FOF40\Configuration\Domain\Models::get
	 * @covers       FOF40\Configuration\Domain\Models::getTablealias
	 *
	 * @dataProvider getTestGetTablealias
	 *
	 * @param   string $key      Key to read
	 * @param   mixed  $default  Default value
	 * @param   mixed  $expected Expected value
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGetTablealias($key, $default, $expected, $message)
	{
		$actual = $this->object->get($this->data, $key, $default);
		$this->assertEquals($expected, $actual, $message);
	}

	public function getTestGetTablealias()
	{
		return [
			['*.tablealias', null, null, 'Do not return undefined values'],
			['Users.tablealias', null, 'folks', 'Return defined values'],
		];
	}

	/**
	 * @covers       FOF40\Configuration\Domain\Models::get
	 * @covers       FOF40\Configuration\Domain\Models::getBehaviors
	 *
	 * @dataProvider getTestGetBehaviors
	 *
	 * @param   string $key      Key to read
	 * @param   array  $expected Expected value
	 * @param   string $message  Failure message
	 *
	 * @return  void
	 */
	public function testGetBehaviors($key, $expected, $exact, $message)
	{
		$actual = $this->object->get($this->data, $key, []);
		if ($exact)
		{
			$this->assertEquals($expected, $actual, $message);
		}
		else
		{
			foreach ($expected as $ex)
			{
				$this->assertTrue(in_array($ex, $actual), $message);
			}
		}
	}

	public function getTestGetBehaviors()
	{
		return [
			['*.behaviors', ['ping', 'pong'], true, 'All behaviours must be read from star model'],
			[
				'notthere.behaviors', ['ping', 'pong'], true,
				'Behaviours of random models must be read from the star model',
			],
			[
				'Orders.behaviors', ['foo', 'bar', 'baz'], true,
				'Non-merged behaviours must only include the explicitly defined behaviours',
			],
			[
				'Users.behaviors', ['ping', 'pong', 'foo', 'bar', 'baz'], true,
				'Merged behaviours must include both explicitly defined and star model behaviours',
			],
		];
	}

	/**
	 * @covers  FOF40\Configuration\Domain\Models::get
	 * @covers  FOF40\Configuration\Domain\Models::getRelations
	 *
	 * @return  void
	 */
	public function testGetRelations()
	{
		$actual = $this->object->get($this->data, 'Orders.relations', []);

		$this->assertCount(3, $actual, 'All relations must be read');
		$this->assertEquals('children', $actual[0]['type'], 'Relation type must match');
		$this->assertEquals('multiple', $actual[1]['type'], 'Relation type must match');
		$this->assertEquals('child', $actual[2]['type'], 'Relation type must match');
	}
}
