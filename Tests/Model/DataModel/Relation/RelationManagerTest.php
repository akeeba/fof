<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\RelationManager;

use FOF40\Model\DataModel\Collection;
use FOF40\Model\DataModel\RelationManager;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once 'RelationManagerDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\RelationManager::<protected>
 * @covers      FOF40\Model\DataModel\RelationManager::<private>
 * @package     FOF40\Tests\DataModel\RelationManager
 */
class RelationManagerTest extends DatabaseTest
{
	/**
	 * @group       RelationManager
	 * @group       RelationManagerRebase
	 * @covers      FOF40\Model\DataModel\RelationManager::rebase
	 */
	public function testRebase()
	{
		$passedModel  = null;
		$fakeRelation = new ClosureHelper([
			'rebase' => function ($closure, $model) use (&$passedModel) {
				$passedModel = $model;
			},
		]);

		$config = [
			'idFieldName' => 'fakeapp_parent_id',
			'tableName'   => '#__fakeapp_parents',
		];

		$model    = $this->buildModel();
		$newModel = new DataModelStub(static::$container, $config);
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->rebase($newModel);

		$newParent = ReflectionHelper::getValue($relation, 'parentModel');

		$this->assertSame($newModel, $passedModel, 'RelationManager::rebase Failed to pass the new model to the relations');
		$this->assertSame($newModel, $newParent, 'RelationManager::rebase Failed to save the new parent model');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerSetDataFromCollection
	 * @covers      FOF40\Model\DataModel\RelationManager::setDataFromCollection
	 */
	public function testSetDataFromCollection()
	{
		$result       = false;
		$fakeRelation = new ClosureHelper([
			'setDataFromCollection' => function () use (&$result) {
				$result = true;
			},
		]);

		$collection = new Collection();
		$model      = $this->buildModel();
		$relation   = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->setDataFromCollection('test', $collection);

		$this->assertTrue($result, 'RelationManager::setDataFromCollection Failed to invoke the correct method');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerSetDataFromCollection
	 * @covers      FOF40\Model\DataModel\RelationManager::setDataFromCollection
	 */
	public function testSetDataFromCollectionException()
	{
		$this->setExpectedException('FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$collection = new Collection();
		$model      = $this->buildModel();
		$relation   = new RelationManager($model);

		$relation->setDataFromCollection('test', $collection);
	}

	/**
	 * @group           RelationManager
	 * @group           RelationManagerAddRelation
	 * @covers          FOF40\Model\DataModel\RelationManager::addRelation
	 * @dataProvider    RelationManagerDataprovider::getTestAddRelation
	 */
	public function testAddRelation($test, $check)
	{
		$msg = 'RelationManager::addRelation %s - Case: ' . $check['case'];

		$model    = $this->buildModel($test['parentModel']);
		$relation = new RelationManager($model);

		if ($check['exception'])
		{
			$this->setExpectedException($check['exception']);
		}

		$result = $relation->addRelation($test['name'], $test['type'], $test['model']);

		$relations = ReflectionHelper::getValue($relation, 'relations');

		$this->assertInstanceOf('FOF40\Model\DataModel', $result, sprintf($msg, 'Should return an instance of the parent model'));
		$this->assertArrayHasKey($check['relation'], $relations, sprintf($msg, 'Failed to set the relation name'));
		$this->assertNotEmpty($relations[$check['relation']], $relations, sprintf($msg, 'The new relation should not be empty'));
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerRemoveRelation
	 * @covers      FOF40\Model\DataModel\RelationManager::removeRelation
	 */
	public function testRemoveRelation()
	{
		$fakeRelation = new ClosureHelper([
			'setDataFromCollection' => function () {
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$result    = $relation->removeRelation('test');
		$relations = ReflectionHelper::getValue($relation, 'relations');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, 'RelationManager::removeRelation Should return the parent model');
		$this->assertArrayNotHasKey('test', $relations, 'RelationManager::removeRelation Failed to remove the relation');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerResetRelations
	 * @covers      FOF40\Model\DataModel\RelationManager::resetRelations
	 */
	public function testResetRelations()
	{
		$fakeRelation = new ClosureHelper([
			'setDataFromCollection' => function () {
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->resetRelations();
		$relations = ReflectionHelper::getValue($relation, 'relations');

		$this->assertEmpty($relations, 'RelationManager::resetRelations Failed to reset the whole relations');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetRelationNames
	 * @covers      FOF40\Model\DataModel\RelationManager::getRelationNames
	 */
	public function testGetRelationNames()
	{
		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => '', 'foobar' => '']);

		$names = $relation->getRelationNames();

		$this->assertEquals([
			'test', 'foobar',
		], $names, 'RelationManager::getRelationNames Failed to return the name of the relations');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetRelation
	 * @covers      FOF40\Model\DataModel\RelationManager::getRelation
	 */
	public function testGetRelation()
	{
		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => 'test']);

		$result = $relation->getRelation('test');

		$this->assertEquals('test', $result, 'RelationManager::getRelation Failed to return the relation');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetRelation
	 * @covers      FOF40\Model\DataModel\RelationManager::getRelation
	 */
	public function testGetRelationException()
	{
		$this->setExpectedException('\FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$relation->getRelation('test');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetNew
	 * @covers      FOF40\Model\DataModel\RelationManager::getNew
	 */
	public function testGetNew()
	{
		$result       = false;
		$fakeRelation = new ClosureHelper([
			'getNew' => function () use (&$result) {
				$result = true;
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->getNew('test');

		$this->assertTrue($result, 'RelationManager::getNew Failed to invoke the correct method');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetNew
	 * @covers      FOF40\Model\DataModel\RelationManager::getNew
	 */
	public function testGetNewException()
	{
		$this->setExpectedException('\FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$relation->getNew('test');
	}

	/**
	 * @group           RelationManager
	 * @group           RelationManagerSave
	 * @covers          FOF40\Model\DataModel\RelationManager::save
	 * @dataProvider    RelationManagerDataprovider::getTestSave
	 */
	public function testSave($test, $check)
	{
		$msg = 'RelationManager::save %s - Case: ' . $check['case'];

		$save         = false;
		$fakeRelation = new ClosureHelper([
			'saveAll' => function () use (&$save, $test) {
				if ($test['exception'] == 'notSupport')
				{
					throw new \FOF40\Model\DataModel\Relation\Exception\SaveNotSupported();
				}
				elseif ($test['exception'] == 'exception')
				{
					throw new \Exception();
				}

				$save = true;
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		if ($check['exception'])
		{
			$this->setExpectedException($check['exception']);
		}

		$result = $relation->save($test['name']);

		$this->assertInstanceOf('FOF40\Model\DataModel', $result, sprintf($msg, 'Should return an instance of the parent model'));
		$this->assertEquals($check['save'], $save, sprintf($msg, 'Failed to correctly invoke save on the relation'));
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetData
	 * @covers      FOF40\Model\DataModel\RelationManager::getData
	 */
	public function testGetData()
	{
		$result       = false;
		$fakeRelation = new ClosureHelper([
			'getData' => function () use (&$result) {
				$result = true;
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->getData('test');

		$this->assertTrue($result, 'RelationManager::getData Failed to invoke the correct method');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetData
	 * @covers      FOF40\Model\DataModel\RelationManager::getData
	 */
	public function testGetDataException()
	{
		$this->setExpectedException('\FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$relation->getData('test');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetForeignKeyMap
	 * @covers      FOF40\Model\DataModel\RelationManager::getForeignKeyMap
	 */
	public function testGetForeignKeyMap()
	{
		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$hasMany = $this->getMockBuilder('FOF40\Model\DataModel\Relation\HasMany')
			->setMethods(['getForeignKeyMap'])
			->setConstructorArgs([$model, 'Fakeapp\Model\Children',])
			->getMock();

		$hasMany->expects($this->once())->method('getForeignKeyMap')->willReturn(null);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $hasMany]);

		$relation->getForeignKeyMap('test');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetForeignKeyMap
	 * @covers      FOF40\Model\DataModel\RelationManager::getForeignKeyMap
	 */
	public function testGetForeignKeyMapException()
	{
		$this->setExpectedException('\FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$relation->getForeignKeyMap('test');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetCountSubquery
	 * @covers      FOF40\Model\DataModel\RelationManager::getCountSubquery
	 */
	public function testGetCountSubquery()
	{
		$result       = false;
		$fakeRelation = new ClosureHelper([
			'getCountSubquery' => function () use (&$result) {
				$result = true;
			},
		]);

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['test' => $fakeRelation]);

		$relation->getCountSubquery('test');

		$this->assertTrue($result, 'RelationManager::getCountSubquery Failed to invoke the correct method');
	}

	/**
	 * @group       RelationManager
	 * @group       RelationManagerGetCountSubquery
	 * @covers      FOF40\Model\DataModel\RelationManager::getCountSubquery
	 */
	public function testGetCountSubqueryException()
	{
		$this->setExpectedException('\FOF40\Model\DataModel\Relation\Exception\RelationNotFound');

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		$relation->getCountSubquery('test');
	}

	/**
	 * @group           RelationManager
	 * @group           RelationManagerCall
	 * @covers          FOF40\Model\DataModel\RelationManager::__call
	 * @dataProvider    RelationManagerDataprovider::getTest__call
	 */
	public function test__call($test, $check)
	{
		$msg    = 'RelationManager::__call %s - Case: ' . $check['case'];
		$result = '';

		$model = $this->buildModel();

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['addRelation', 'getData'])
			->setConstructorArgs([$model])
			->getMock();

		$getData     = $relation->expects($check['get'] ? $this->once() : $this->never())->method('getData')->willReturnSelf();
		$addRelation = $relation->expects($check['add'] ? $this->once() : $this->never())->method('addRelation')->willReturnSelf();

		if ($check['exception'])
		{
			$this->setExpectedException($check['exception']);
		}

		$method = $test['method'];

		switch ($test['arguments'])
		{
			case 0:
				$getData->with($this->equalTo($check['name']));
				$result = $relation->$method();
				break;

			case 1:
				$getData->with($this->equalTo($check['name']), $this->equalTo(null));
				$addRelation->with($this->equalTo(null), $this->equalTo($check['name']));
				$result = $relation->$method(null);
				break;

			case 2:
				$getData->with($this->equalTo($check['name']), $this->equalTo(null), $this->equalTo(null));
				$addRelation->with($this->equalTo(null), $this->equalTo($check['name']), $this->equalTo(null));
				$result = $relation->$method(null, null);
				break;

			case 3:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1);
				break;

			case 4:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1, 1);
				break;

			case 5:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1, 1, 1);
				break;

			case 6:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1, 1, 1, 1);
				break;

			case 7:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1, 1, 1, 1, 1);
				break;

			case 8:
				$addRelation->with($this->equalTo(1), $this->equalTo($check['name']), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1), $this->equalTo(1));
				$result = $relation->$method(1, 1, 1, 1, 1, 1, 1, 1);
				break;
		}

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel\\RelationManager', $result, sprintf($msg, 'Should return an instance of itself'));
	}

	/**
	 * @group           RelationManager
	 * @group           RelationManagerIsMagicMethod
	 * @covers          FOF40\Model\DataModel\RelationManager::isMagicMethod
	 * @dataProvider    RelationManagerDataprovider::getTestIsMagicMethod
	 */
	public function testIsMagicMethod($test, $check)
	{
		$msg = 'RelationManager::isMagicMethod %s - Case: ' . $check['case'];

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['foobar' => '']);

		$result = $relation->isMagicMethod($test['method']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the corret result'));
	}

	/**
	 * @group           RelationManager
	 * @group           RelationManagerIsMagicProperty
	 * @covers          FOF40\Model\DataModel\RelationManager::isMagicProperty
	 * @dataProvider    RelationManagerDataprovider::getTestIsMagicProperty
	 */
	public function testIsMagicProperty($test, $check)
	{
		$msg = 'RelationManager::isMagicProperty %s - Case: ' . $check['case'];

		$model    = $this->buildModel();
		$relation = new RelationManager($model);

		ReflectionHelper::setValue($relation, 'relations', ['foobar' => '']);

		$result = $relation->isMagicProperty($test['name']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the corret result'));
	}

	protected function buildModel($class = null)
	{
		if (!$class)
		{
			$class = '\\FOF40\\Tests\\Stubs\\Model\\DataModelStub';
		}

		$config = [
			'idFieldName' => 'fakeapp_parent_id',
			'tableName'   => '#__fakeapp_parents',
		];

		return new $class(static::$container, $config);
	}
}
