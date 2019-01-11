<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\Utils;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Utils\Collection;

class CollectionTest extends FOFTestCase
{
	public function testFirstReturnsFirstItemInCollection()
	{
		$c = new Collection(['foo', 'bar']);
		$this->assertEquals('foo', $c->first());
	}

	public function testLastReturnsLastItemInCollection()
	{
		$c = new Collection(['foo', 'bar']);

		$this->assertEquals('bar', $c->last());
	}

	public function testPopReturnsAndRemovesLastItemInCollection()
	{
		$c = new Collection(['foo', 'bar']);

		$this->assertEquals('bar', $c->pop());
		$this->assertEquals('foo', $c->first());
	}

	public function testShiftReturnsAndRemovesFirstItemInCollection()
	{
		$c = new Collection(['foo', 'bar']);

		$this->assertEquals('foo', $c->shift());
		$this->assertEquals('bar', $c->first());
	}

	public function testEmptyCollectionIsEmpty()
	{
		$c = new Collection();

		$this->assertTrue($c->isEmpty());
	}

	public function testToArrayCallsToArrayOnEachItemInCollection()
	{
		$item1 = $this->getMockBuilder('FOF40\\Registry\\Registry')
			->setMethods(['toArray'])
			->getMock();
		$item1
			->expects($this->once())
			->method('toArray')
			->will($this->returnValue('foo.array'));

		$item2 = $this->getMockBuilder('FOF40\\Registry\\Registry')
			->setMethods(['toArray'])
			->getMock();

		$item2
			->expects($this->once())
			->method('toArray')
			->will($this->returnValue('bar.array'));

		$c       = new Collection([$item1, $item2]);
		$results = $c->toArray();

		$this->assertEquals(['foo.array', 'bar.array'], $results);
	}

	public function testToJsonEncodesTheToArrayResult()
	{
		$c = $this->getMockBuilder('FOF40\Utils\Collection')
			->setMethods(['toArray'])
			->getMock();
		$c->expects($this->once())->method('toArray')->will($this->returnValue('foo'));
		$results = $c->toJson();

		$this->assertEquals(json_encode('foo'), $results);
	}

	public function testCastingToStringJsonEncodesTheToArrayResult()
	{
		$c = $this->getMockBuilder('FOF40\Utils\Collection')
			->setMethods(['toArray'])
			->getMock();

		$c->expects($this->once())->method('toArray')->will($this->returnValue('foo'));

		$this->assertEquals(json_encode('foo'), (string) $c);
	}

	public function testOffsetAccess()
	{
		$c = new Collection(['name' => 'foo']);
		$this->assertEquals('foo', $c['name']);
		$c['name'] = 'bar';
		$this->assertEquals('bar', $c['name']);
		$this->assertTrue(isset($c['name']));
		unset($c['name']);
		$this->assertFalse(isset($c['name']));
		$c[] = 'baz';
		$this->assertEquals('baz', $c[0]);
	}

	public function testCountable()
	{
		$c = new Collection(['foo', 'bar']);
		$this->assertEquals(2, count($c));
	}

	public function testIterable()
	{
		$c = new Collection(['foo']);
		$this->assertInstanceOf('ArrayIterator', $c->getIterator());
		$this->assertEquals(['foo'], $c->getIterator()->getArrayCopy());
	}

	public function testCachingIterator()
	{
		$c = new Collection(['foo']);
		$this->assertInstanceOf('CachingIterator', $c->getCachingIterator());
	}

	public function testFilter()
	{
		$c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);
		$this->assertEquals([1 => ['id' => 2, 'name' => 'World']], $c->filter(function ($item) {
			return $item['id'] == 2;
		})->all());
	}

	public function testValues()
	{
		$c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);
		$this->assertEquals([['id' => 2, 'name' => 'World']], $c->filter(function ($item) {
			return $item['id'] == 2;
		})->values()->all());
	}

	public function testFlatten()
	{
		$c = new Collection([['#foo', '#bar'], ['#baz']]);
		$this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
	}

	public function testMergeArray()
	{
		$c = new Collection(['name' => 'Hello']);
		$this->assertEquals(['name' => 'Hello', 'id' => 1], $c->merge(['id' => 1])->all());
	}

	public function testMergeCollection()
	{
		$c = new Collection(['name' => 'Hello']);
		$this->assertEquals(['name' => 'World', 'id' => 1], $c->merge(new Collection([
			'name' => 'World', 'id' => 1,
		]))->all());
	}

	public function testDiffCollection()
	{
		$c = new Collection(['id' => 1, 'first_word' => 'Hello']);
		$this->assertEquals(['id' => 1], $c->diff(new Collection([
			'first_word' => 'Hello', 'last_word' => 'World',
		]))->all());
	}

	public function testIntersectCollection()
	{
		$c = new Collection(['id' => 1, 'first_word' => 'Hello']);
		$this->assertEquals(['first_word' => 'Hello'], $c->intersect(new Collection([
			'first_world' => 'Hello', 'last_word' => 'World',
		]))->all());
	}

	public function testUnique()
	{
		$c = new Collection(['Hello', 'World', 'World']);
		$this->assertEquals(['Hello', 'World'], $c->unique()->all());
	}

	public function testCollapse()
	{
		$data = new Collection([[$object1 = new \StdClass], [$object2 = new \StdClass]]);
		$this->assertEquals([$object1, $object2], $data->collapse()->all());
	}

	public function testSort()
	{
		$data = new Collection([5, 3, 1, 2, 4]);
		$data->sort(function ($a, $b) {
			if ($a === $b)
			{
				return 0;
			}

			return ($a < $b) ? -1 : 1;
		});

		$this->assertEquals(range(1, 5), array_values($data->all()));
	}

	public function testSortBy()
	{
		$data = new Collection(['foo', 'bar']);
		$data = $data->sortBy(function ($x) {
			return $x;
		});

		$this->assertEquals(['bar', 'foo'], array_values($data->all()));

		$data = new Collection(['bar', 'foo']);
		$data->sortByDesc(function ($x) {
			return $x;
		});

		$this->assertEquals(['foo', 'bar'], array_values($data->all()));
	}

	public function testSortByString()
	{
		$data = new Collection([['name' => 'foo'], ['name' => 'bar']]);
		$data = $data->sortBy('name');

		$this->assertEquals([['name' => 'bar'], ['name' => 'foo']], array_values($data->all()));
	}

	public function testReverse()
	{
		$data     = new Collection(['foo', 'bar']);
		$reversed = $data->reverse();

		$this->assertEquals(['bar', 'foo'], array_values($reversed->all()));
	}

	public function testListsWithArrayAndObjectValues()
	{
		$data = new Collection([(object) ['name' => 'kot', 'email' => 'foo'], ['name' => 'lol', 'email' => 'bar']]);
		$this->assertEquals(['kot' => 'foo', 'lol' => 'bar'], $data->lists('email', 'name'));
		$this->assertEquals(['foo', 'bar'], $data->lists('email'));
	}

	public function testImplode()
	{
		$data = new Collection([['name' => 'kot', 'email' => 'foo'], ['name' => 'lol', 'email' => 'bar']]);
		$this->assertEquals('foobar', $data->implode('email'));
		$this->assertEquals('foo,bar', $data->implode('email', ','));
	}

	public function testTake()
	{
		$data = new Collection(['foo', 'bar', 'baz']);
		$data = $data->take(2);
		$this->assertEquals(['foo', 'bar'], $data->all());
	}

	public function testRandom()
	{
		$data   = new Collection([1, 2, 3, 4, 5, 6]);
		$random = $data->random();
		$this->assertInternalType('integer', $random);
		$this->assertContains($random, $data->all());
		$random = $data->random(3);
		$this->assertCount(3, $random);
	}

	public function testTakeLast()
	{
		$data = new Collection(['foo', 'bar', 'baz']);
		$data = $data->take(-2);
		$this->assertEquals(['bar', 'baz'], $data->all());
	}

	public function testTakeAll()
	{
		$data = new Collection(['foo', 'bar', 'baz']);
		$data = $data->take();
		$this->assertEquals(['foo', 'bar', 'baz'], $data->all());
	}

	public function testMakeMethod()
	{
		$collection = Collection::make('foo');
		$this->assertEquals(['foo'], $collection->all());
	}

	public function testSplice()
	{
		$data = new Collection(['foo', 'baz']);
		$data->splice(1, 0, 'bar');
		$this->assertEquals(['foo', 'bar', 'baz'], $data->all());

		$data = new Collection(['foo', 'baz']);
		$data->splice(1, 1);
		$this->assertEquals(['foo'], $data->all());

		$data = new Collection(['foo', 'baz']);
		$cut  = $data->splice(1, 1, 'bar');
		$this->assertEquals(['foo', 'bar'], $data->all());
		$this->assertEquals(['baz'], $cut->all());
	}

	public function testGetListValueWithAccessors()
	{
		$model    = new TestAccessorFOF40TestStub(['some' => 'foo']);
		$modelTwo = new TestAccessorFOF40TestStub(['some' => 'bar']);
		$data     = new Collection([$model, $modelTwo]);

		$this->assertEquals(['foo', 'bar'], $data->lists('some'));
	}

	public function testTransform()
	{
		$data = new Collection(['foo', 'bar', 'baz']);
		$data->transform(function ($item) {
			return strrev($item);
		});
		$this->assertEquals(['oof', 'rab', 'zab'], array_values($data->all()));
	}

	public function testFirstWithCallback()
	{
		$data   = new Collection(['foo', 'bar', 'baz']);
		$result = $data->first(function ($key, $value) {
			return $value === 'bar';
		});
		$this->assertEquals('bar', $result);
	}

	public function testFirstWithCallbackAndDefault()
	{
		$data   = new Collection(['foo', 'bar']);
		$result = $data->first(function ($key, $value) {
			return $value === 'baz';
		}, 'default');
		$this->assertEquals('default', $result);
	}

	public function testGroupByAttribute()
	{
		$data   = new Collection([
			['rating' => 1, 'name' => '1'], ['rating' => 1, 'name' => '2'], ['rating' => 2, 'name' => '3'],
		]);
		$result = $data->groupBy('rating');
		$this->assertEquals([
			1 => [['rating' => 1, 'name' => '1'], ['rating' => 1, 'name' => '2']], 2 => [['rating' => 2, 'name' => '3']],
		], $result->toArray());
	}

	public function testGettingSumFromCollection()
	{
		$c = new Collection([(object) ['foo' => 50], (object) ['foo' => 50]]);
		$this->assertEquals(100, $c->sum('foo'));

		$c = new Collection([(object) ['foo' => 50], (object) ['foo' => 50]]);
		$this->assertEquals(100, $c->sum(function ($i) {
			return $i->foo;
		}));
	}

	public function testGettingSumFromEmptyCollection()
	{
		$c = new Collection();
		$this->assertEquals(0, $c->sum('foo'));
	}
}

class TestAccessorFOF40TestStub
{
	protected $attributes = [];

	public function __construct($attributes)
	{
		$this->attributes = $attributes;
	}


	public function __get($attribute)
	{
		$accessor = 'get' . lcfirst($attribute) . 'Attribute';
		if (method_exists($this, $accessor))
		{
			return $this->$accessor();
		}

		return $this->$attribute;
	}


	public function getSomeAttribute()
	{
		return $this->attributes['some'];
	}
}
