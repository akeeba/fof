<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Relation;

use FOF40\Model\DataModel;
use FOF40\Model\DataModel\Collection;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\RelationStub;

require_once __DIR__ . '/RelationDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Relation::<protected>
 * @covers      FOF40\Model\DataModel\Relation::<private>
 * @package     FOF40\Tests\DataModel\Relation
 */
class RelationTest extends DatabaseTest
{
	/**
	 * @group           Relation
	 * @group           RelationContruct
	 * @covers          FOF40\Model\DataModel\Relation::__construct
	 */
	public function testConstruct()
	{
		$msg      = 'Reflection::__construct %s';
		$model    = $this->buildModel();
		$relation = new RelationStub($model, 'Children', 'localkey', 'foreignkey', 'pivotTable', 'pvLocal', 'pvForeign');

		$this->assertSame($model, ReflectionHelper::getValue($relation, 'parentModel'), sprintf($msg, 'Failed to set the parent model'));
		$this->assertEquals('Children', ReflectionHelper::getValue($relation, 'foreignModelClass'), sprintf($msg, 'Failed to set the foreign model'));
		$this->assertEquals('localkey', ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
		$this->assertEquals('foreignkey', ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
		$this->assertEquals('pivotTable', ReflectionHelper::getValue($relation, 'pivotTable'), sprintf($msg, 'Failed to set the pivot table'));
		$this->assertEquals('pvLocal', ReflectionHelper::getValue($relation, 'pivotLocalKey'), sprintf($msg, 'Failed to set the pivot local key'));
		$this->assertEquals('Children', ReflectionHelper::getValue($relation, 'foreignModelName'), sprintf($msg, 'Failed to set the foreign model name'));
	}

	/**
	 * @group           Relation
	 * @group           RelationReset
	 * @covers          FOF40\Model\DataModel\Relation::reset
	 */
	public function testReset()
	{
		$msg = 'Relation::reset %s';

		$model    = $this->buildModel();
		$relation = new RelationStub($model, 'Children');

		ReflectionHelper::setValue($relation, 'data', [1, 2, 3]);
		ReflectionHelper::setValue($relation, 'foreignKeyMap', [1, 2, 3]);

		$result = $relation->reset();

		$this->assertInstanceOf('FOF40\Model\DataModel\Relation', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEmpty(ReflectionHelper::getValue($relation, 'data'), sprintf($msg, 'Should empty the internal data'));
		$this->assertEmpty(ReflectionHelper::getValue($relation, 'foreignKeyMap'), sprintf($msg, 'Should empty the foreign key map'));
	}

	/**
	 * @group           Relation
	 * @group           RelationRebase
	 * @covers          FOF40\Model\DataModel\Relation::rebase
	 */
	public function testRebase()
	{
		$model = $this->buildModel();

		$relation = $this->getMockBuilder('FOF40\Tests\Stubs\Model\RelationStub')
			->setMethods(['reset'])
			->setConstructorArgs([$model, 'Fakeapp\Model\Children'])
			->getMock();

		$relation->method('reset')->willReturnSelf();

		$newModel = $this->buildModel('\Fakeapp\Site\Model\Datafoobars');

		$result = $relation->rebase($newModel);

		$this->assertInstanceOf('FOF40\Model\DataModel\Relation', $result, 'Relation::rebase should return an instance of itself');
		$this->assertSame($newModel, ReflectionHelper::getValue($relation, 'parentModel'), 'Relation::rebase Failed to change the parent model');
	}

	/**
	 * @group           Relation
	 * @group           RelationGetData
	 * @covers          FOF40\Model\DataModel\Relation::getData
	 * @dataProvider    RelationDataprovider::getTestGetData
	 */
	public function testGetData($test, $check)
	{
		$msg           = 'Relation::getData %s - Case: ' . $check['case'];
		$applyCallback = false;

		$model    = $this->buildModel();
		$relation = new RelationStub($model, 'Children');
		$relation->setupMocks([
			'filterForeignModel' => function () use ($test) {
				return $test['mock']['filter'];
			},
		]);

		ReflectionHelper::setValue($relation, 'data', $test['mock']['data']);

		$callable = function () use (&$applyCallback) {
			$applyCallback = true;
		};

		$result = $relation->getData($callable);

		$instanceok = false;

		if ($result instanceof Collection || $result instanceof DataModel)
		{
			$instanceok = true;
		}

		$this->assertTrue($instanceok, sprintf($msg, 'Should return an instance of Collection or DataModel'));
		$this->assertEquals($check['applyCallback'], $applyCallback, sprintf($msg, 'Failed to correctly apply the callback'));
		$this->assertCount($check['count'], $result, sprintf($msg, 'Failed to return the correct amount of data'));
	}

	/**
	 * @group           Relation
	 * @group           RelationSetDataFromCollection
	 * @covers          FOF40\Model\DataModel\Relation::setDataFromCollection
	 */
	public function testSetDataFromCollection()
	{
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$childrenModel = new \Fakeapp\Site\Model\Children(static::$container);
		$model         = $this->buildModel();

		$model->find(2);
		$relation = new RelationStub($model, 'Children', 'fakeapp_parent_id', 'fakeapp_parent_id');

		$items[0] = clone $childrenModel;
		$items[1] = clone $childrenModel;

		$items[0]->find(1); // This child record IS NOT related to the current parent
		$items[1]->find(3); // This child record IS related to the current parent

		$collection = new Collection($items);

		$relation->setDataFromCollection($collection);

		$data = ReflectionHelper::getValue($relation, 'data');

		// I should have only one record, since the other one is not related to the current parent model
		$this->assertCount(1, $data);
	}

	/**
	 * @group           Relation
	 * @group           RelationSaveAll
	 * @covers          FOF40\Model\DataModel\Relation::saveAll
	 */
	public function testSaveAll()
	{
		$model    = $this->buildModel();
		$relation = new RelationStub($model, '\Children');

		$config = [
			'idFieldName' => 'fakeapp_parent_id',
			'tableName'   => '#__fakeapp_parents',
		];

		$item = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$item->expects($this->once())->method('save')->willReturn(null);

		$collection = new Collection([$item]);

		ReflectionHelper::setValue($relation, 'data', $collection);

		$relation->saveAll();
	}

	/**
	 * @group           Relation
	 * @group           RelationGetforeignKeyMap
	 * @covers          FOF40\Model\DataModel\Relation::getForeignKeyMap
	 */
	public function testGetForeignKeyMap()
	{
		$model    = $this->buildModel();
		$relation = new RelationStub($model, 'Children');

		$keymap = [1, 2, 3];

		ReflectionHelper::setValue($relation, 'foreignKeyMap', $keymap);

		$result = $relation->getForeignKeyMap();

		$this->assertEquals($keymap, $result, 'Relation::getForeignKeyMap Returned the wrong result');
	}

	/**
	 * @param   string $class
	 *
	 * @return \FOF40\Model\DataModel
	 */
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
