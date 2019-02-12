<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Collection;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\DataModelStub;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'RelationDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel::<protected>
 * @covers      FOF40\Model\DataModel::<private>
 * @package     FOF40\Tests\DataModel
 */
class DataModelRealtionTest extends DatabaseTest
{
	/**
	 * @group           DataModel
	 * @group           DataModelSaveTouches
	 * @covers          FOF40\Model\DataModel::save
	 */
	public function testSaveTouches()
	{
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		// I need to fake the user id, since in CLI I don't have one
		$container       = new TestContainer();
		$platform        = $container->platform;
		$platform::$user = (object) ['id' => 99];

		$config = [
			'autoChecks'  => false,
			'idFieldName' => 'fakeapp_parent_id',
			'tableName'   => '#__fakeapp_parents',
			'relations'   => [
				[
					'itemName'          => 'children',
					'type'              => 'hasMany',
					'foreignModelClass' => 'Children',
					'localKey'          => 'fakeapp_parent_id',
					'foreignKey'        => 'fakeapp_parent_id',
				],
			],
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['check', 'reorder'])
			->setConstructorArgs([$container, $config])
			->getMock();

		ReflectionHelper::setValue($model, 'touches', ['children']);

		$model->find(1);
		$model->save(null, null, null);

		$db       = JoomlaFactory::getDbo();
		$query    = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_children'))
			->where($db->qn('fakeapp_parent_id') . ' = ' . $db->q(1));
		$children = $db->setQuery($query)->loadObjectList();

		foreach ($children as $child)
		{
			$this->assertEquals(99, $child->modified_by, 'DataModel::save Failed to touch "modified_by" field in children record');
			$this->assertNotEquals('0000-00-00 00:00:00', $child->modified_on, 'DataModel::save Failed to touch "modified_on" field in children record');
		}
	}

	/**
	 * @group           DataModel
	 * @group           DataModelPush
	 * @covers          FOF40\Model\DataModel::push
	 * @dataProvider    DataModelRelationDataprovider::getTestPush
	 */
	public function testPush($test, $check)
	{
		$msg = 'DataModel::push %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['getRelationNames', 'save'])
			->setConstructorArgs([$model])
			->getMock();

		$relation->method('getRelationNames')->willReturn($test['mock']['names']);
		$relation->method('save')->with($this->callback(function ($name) use (&$check) {
			$current = array_shift($check['save']);

			return ($name == $current) && $current;
		}));

		ReflectionHelper::setValue($model, 'relationManager', $relation);
		ReflectionHelper::setValue($model, 'touches', $test['mock']['touches']);

		$result  = $model->push(null, '', null, $test['relations']);
		$touches = ReflectionHelper::getValue($model, 'touches');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['touches'], $touches, sprintf($msg, 'Failed to handle touches array'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelEagerLoad
	 * @covers          FOF40\Model\DataModel::eagerLoad
	 * @dataProvider    DataModelRelationDataprovider::getTestEagerLoad
	 */
	public function testEagerLoad($test, $check)
	{
		$globRelation = null;
		$items        = [];
		$msg          = 'DataModel::eagerLoad %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		// The collection should contain items?
		if ($test['items'])
		{
			$fakeRelationManager = new ClosureHelper([
				'setDataFromCollection' => function () {
				},
			]);

			$mockedModelRelation = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
				->setMethods(['getRelations'])
				->setConstructorArgs([static::$container, $config])
				->getMock();

			$mockedModelRelation->method('getRelations')->willReturn($fakeRelationManager);

			$item    = clone $mockedModelRelation;
			$items[] = $item;
		}

		$collection = Collection::make($items);

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getRelations'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['getData', 'getForeignKeyMap'])
			->setConstructorArgs([$model])
			->getMock();

		// Let's check if the logic of swapping the callback function when it's not callable works
		$relation->expects($check['getData'] ? $this->atLeastOnce() : $this->never())->method('getData')->with(
			$this->equalTo(isset($check['getData']['relation']) ? $check['getData']['relation'] : null),
			$this->callback(function ($callback = '') use (&$check) {
				if ($check['getData']['callback'] == 'function')
				{
					$checkCallback = is_callable($callback);
				}
				else
				{
					$checkCallback = ($callback == $check['getData']['callback']);
				}

				return $checkCallback;
			})
		);

		$model->method('getRelations')->willReturn($relation);

		ReflectionHelper::setValue($model, 'eagerRelations', $test['mock']['eager']);

		$result = $model->eagerLoad($collection, $test['relations']);

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelHas
	 * @covers          FOF40\Model\DataModel::has
	 * @dataProvider    DataModelRelationDataprovider::getTestHas
	 */
	public function testHas($test, $check)
	{
		$msg = 'DataModel::has %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['addBehaviour'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['add'] ? $this->once() : $this->never())->method('addBehaviour');

		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['hasObserverClass'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->method('hasObserverClass')->willReturn($test['mock']['hasClass']);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);
		ReflectionHelper::setValue($model, 'relationFilters', $test['mock']['filters']);


		$result  = $model->has($test['relation'], $test['method'], $test['values'], $test['replace']);
		$filters = $model->getRelationFilters();

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['filters'], $filters, sprintf($msg, 'Failed to correctly add the filter'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelHas
	 * @covers          FOF40\Model\DataModel::has
	 */
	public function testHasException()
	{
		$this->setExpectedException('FOF40\Model\DataModel\Exception\InvalidSearchMethod');

		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = new DataModelStub(static::$container, $config);
		$model->has('posts', 'wrong', true);
	}

	/**
	 * @group           DataModel
	 * @group           DataModelGetRelations
	 * @covers          FOF40\Model\DataModel::getRelations
	 */
	public function testGetRelations()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = new DataModelStub(static::$container, $config);

		$refl = ReflectionHelper::getValue($model, 'relationManager');
		$obj  = $model->getRelations();

		$this->assertSame($refl, $obj, 'DataModel::getRelations failed to return the internal object');
	}

	/**
	 * @group           DataModel
	 * @group           DataModelWhereHas
	 * @covers          FOF40\Model\DataModel::whereHas
	 */
	public function testWhereHas()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['has'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->method('has')->with(
			$this->equalTo('dummy'),
			$this->equalTo('callback'),
			$this->callback(function ($callback) {
				return is_callable($callback);
			}),
			$this->equalTo(true)
		);

		$result = $model->whereHas('dummy', function () {
		}, true);

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, 'DataModel::whereHas Should return an instance of itself');
	}

	/**
	 * @group           DataModel
	 * @group           DataModelGetRelationFilters
	 * @covers          FOF40\Model\DataModel::getRelationFilters
	 */
	public function testGetRelationFilters()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = new DataModelStub(static::$container, $config);

		$filters = ['foo', 'bar'];

		ReflectionHelper::setValue($model, 'relationFilters', $filters);
		$obj = $model->getRelationFilters();

		$this->assertSame($filters, $obj, 'DataModel::relationFilters failed to return the internal array');
	}

	/**
	 * @group           DataModel
	 * @group           DataModelGetTouches
	 * @covers          FOF40\Model\DataModel::getTouches
	 */
	public function testGetTouches()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = new DataModelStub(static::$container, $config);

		$touches = ['foo', 'bar'];

		ReflectionHelper::setValue($model, 'touches', $touches);
		$obj = $model->getTouches();

		$this->assertSame($touches, $obj, 'DataModel::getTouches failed to return the internal array');
	}
}
