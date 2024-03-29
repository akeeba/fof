<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once 'MagicMethodsDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel::<protected>
 * @covers      FOF40\Model\DataModel::<private>
 * @package     FOF40\Tests\DataModel
 */
class DataModelMagicMethodsTest extends DatabaseTest
{
	/**
	 * @group           DataModel
	 * @group           DataModelConstruct
	 * @covers          FOF40\Model\DataModel::__construct
	 * @dataProvider    MagicMethodsDataprovider::getTest__construct
	 */
	public function test__construct($test, $check)
	{
		$msg        = 'DataModel::__construct %s - Case: ' . $check['case'];
		$counterApp = 0;

		$config = [
			'idFieldName'      => $test['id'],
			'tableName'        => $test['table'],
			'knownFields'      => $test['knownFields'],
			'autoChecks'       => $test['autoChecks'],
			'fieldsSkipChecks' => $test['skipChecks'],
			'aliasFields'      => $test['aliasFields'],
			'behaviours'       => $test['behaviours'],
			'fillable_fields'  => $test['fillable'],
			'guarded_fields'   => $test['guarded'],
			'relations'        => $test['relations'],
		];

		// Setup the class but do not instantiate it, so we to mock the methods
		$model = $this->getMockBuilder('FOF40\\Model\\DataModel')
			->setMethods(['getName', 'addBehaviour', 'getState'])
			->setConstructorArgs([])
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		$model->method('getName')->willReturn('test');
		$model->expects($this->exactly($check['addBehaviour']))->method('addBehaviour');
		$model->method('getState')->willReturnCallback(function ($field) use ($test) {
			if (isset($test['mock']['state'][$field]))
			{
				return $test['mock']['state'][$field];
			}

			return null;
		});

		//Finally, let's invoke our crafted mock
		$model->__construct(static::$container, $config);

		$id          = ReflectionHelper::getValue($model, 'idFieldName');
		$tableName   = ReflectionHelper::getValue($model, 'tableName');
		$knownFields = ReflectionHelper::getValue($model, 'knownFields');
		$autoChecks  = ReflectionHelper::getValue($model, 'autoChecks');
		$skipChecks  = ReflectionHelper::getValue($model, 'fieldsSkipChecks');
		$aliasFields = ReflectionHelper::getValue($model, 'aliasFields');
		$fillable    = ReflectionHelper::getValue($model, 'fillable');
		$autoFill    = ReflectionHelper::getValue($model, 'autoFill');
		$guarded     = ReflectionHelper::getValue($model, 'guarded');
		$relations   = $model->getRelations()->getRelationNames();

		$this->assertEquals($check['id'], $id, sprintf($msg, 'Failed to set the id'));
		$this->assertEquals($check['table'], $tableName, sprintf($msg, 'Failed to set the table name'));
		$this->assertEquals($check['autochecks'], $autoChecks, sprintf($msg, 'Failed to set the autochecks'));
		$this->assertEquals($check['skipchecks'], $skipChecks, sprintf($msg, 'Failed to set the field to skip in auto checks'));
		$this->assertEquals($check['alias'], $aliasFields, sprintf($msg, 'Failed to set the alias field'));
		$this->assertEquals($check['fillable'], $fillable, sprintf($msg, 'Failed to set the fillable fields'));
		$this->assertEquals($check['autofill'], $autoFill, sprintf($msg, 'Failed to set the autofill flag'));
		$this->assertEquals($check['guarded'], $guarded, sprintf($msg, 'Failed to set the guarded fields'));
		$this->assertEquals($check['relations'], $relations, sprintf($msg, 'Failed to set the relations'));
		$this->assertEquals($check['counterApp'], $counterApp, sprintf($msg, 'Failed to correctly get the container from the Application'));

		if (!is_null($check['fields']))
		{
			$db          = $model->getDbo();
			$expected    = $this->_normalizeTableFields($check['fields'], $db);
			$knownFields = $this->_normalizeTableFields($knownFields, $db);

			$this->assertEquals($expected, $knownFields, sprintf($msg, 'Failed to set the known fields'));
		}

		foreach ($check['values'] as $field => $value)
		{
			$actual = $model->getFieldValue($field);
			$this->assertEquals($value, $actual, sprintf($msg, 'Failed to set the value of an autofill field'));
		}
	}

	/**
	 * @group           DataModel
	 * @group           DataModelConstruct
	 * @covers          FOF40\Model\DataModel::__construct
	 */
	public function test__constructException()
	{
		$this->setExpectedException('FOF40\Model\DataModel\Exception\NoTableColumns');

		$config = [
			'idFieldName' => 'id',
			'tableName'   => '#__wrongtable',
		];

		new DataModelStub(static::$container, $config);
	}

	/**
	 * @group           DataModel
	 * @group           DataModelCall
	 * @covers          FOF40\Model\DataModel::__call
	 * @dataProvider    MagicMethodsDataprovider::getTest__call
	 */
	public function test__call($test, $check)
	{
		$msg = 'DataModel::__call %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = new DataModelStub(static::$container, $config);

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['isMagicMethod', '__call'])
			->setConstructorArgs([$model])
			->getMock();

		$relation->expects($check['magic'] ? $this->once() : $this->never())->method('isMagicMethod')->willReturn($test['mock']['magic']);
		$relation->expects($check['relationCall'] ? $this->once() : $this->never())->method('__call')->willReturn(null);

		ReflectionHelper::setValue($model, 'relationManager', $relation);

		$method = $test['method'];

		// I have to use this syntax to check when I don't pass any argument
		// N.B. If I use the __call syntax to set a property, I have to use a REAL property, otherwise the __set magic
		// method kicks in and its behavior it's out the scope of this test
		if (isset($test['argument']))
		{
			$result = $model->$method($test['argument'][0], $test['argument'][1]);
		}
		else
		{
			$result = $model->$method();
		}

		$count    = isset($model->methodCounter[$check['method']]) ? $model->methodCounter[$check['method']] : 0;
		$property = ReflectionHelper::getValue($model, $check['property']);

		if (is_object($result))
		{
			$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		}
		else
		{
			$this->assertNull($result, sprintf($msg, 'Should return null when the relation manager is involved'));
		}

		$this->assertEquals($check['count'], $count, sprintf($msg, 'Invoked the specific caller method a wrong amount of times'));
		$this->assertEquals($check['value'], $property, sprintf($msg, 'Failed to set the property'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModel__isset
	 * @covers          FOF40\Model\DataModel::__isset
	 * @dataProvider    MagicMethodsDataprovider::getTest__isset
	 */
	public function test__isset($test, $check)
	{
		$msg = 'DataModel::__isset %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getFieldValue'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['getField'] ? $this->once() : $this->never())->method('getFieldValue')->with($check['getField'])
			->willReturn($test['mock']['getField']);

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['isMagicProperty', '__get'])
			->setConstructorArgs([$model])
			->getMock();

		$relation->expects($check['magic'] ? $this->once() : $this->never())->method('isMagicProperty')->with($check['magic'])
			->willReturn($test['mock']['magic']);
		$relation->expects($check['relationGet'] ? $this->once() : $this->never())->method('__get')->willReturn($test['mock']['relationGet']);

		ReflectionHelper::setValue($model, 'relationManager', $relation);

		$property = $test['property'];

		ReflectionHelper::setValue($model, 'aliasFields', $test['mock']['alias']);

		$isset = isset($model->$property);

		$this->assertEquals($check['isset'], $isset, sprintf($msg, 'Failed to correctly detect if a property is set'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModel__get
	 * @covers          FOF40\Model\DataModel::__get
	 * @dataProvider    MagicMethodsDataprovider::getTest__get
	 */
	public function test__get($test, $check)
	{
		$msg = 'DataModel::__get %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getFieldValue', 'getState'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['getField'] ? $this->once() : $this->never())->method('getFieldValue')->with($check['getField'])
			->willReturn($test['mock']['getField']);

		$model->expects($check['getState'] ? $this->once() : $this->never())->method('getState')->with($check['getState'])
			->willReturn($test['mock']['getState']);

		$relation = $this->getMockBuilder('FOF40\\Model\\DataModel\\RelationManager')
			->setMethods(['isMagicProperty', '__get'])
			->setConstructorArgs([$model])
			->getMock();

		$relation->expects($check['magic'] ? $this->once() : $this->never())->method('isMagicProperty')->with($check['magic'])
			->willReturn($test['mock']['magic']);
		$relation->expects($check['relationGet'] ? $this->once() : $this->never())->method('__get')->willReturn($test['mock']['relationGet']);

		ReflectionHelper::setValue($model, 'relationManager', $relation);

		$property = $test['property'];

		ReflectionHelper::setValue($model, 'aliasFields', $test['mock']['alias']);

		$get = $model->$property;

		$this->assertEquals($check['get'], $get, sprintf($msg, 'Failed to get the property value'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModel__get
	 * @covers          FOF40\Model\DataModel::__get
	 */
	public function test__getInput()
	{
		$containerInp = static::$container->input;

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = new DataModelStub(static::$container, $config);

		$input = $model->input;

		$this->assertSame($containerInp, $input, 'DataModel::__get Should return the container input');
	}

	/**
	 * @group           DataModel
	 * @group           DataModel__set
	 * @covers          FOF40\Model\DataModel::__set
	 * @dataProvider    MagicMethodsDataprovider::getTest__set
	 */
	public function test__set($test, $check)
	{
		$msg = 'DataModel::__set %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['setFieldValue', 'setState', '__call'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['call'] ? $this->once() : $this->never())->method('__call')->willReturn(null);

		$model->expects($check['setField'] ? $this->once() : $this->never())->method('setFieldValue')->with($this->equalTo($check['setField']))->willReturn(null);
		$model->expects($check['setState'] ? $this->once() : $this->never())->method('setState')->with($this->equalTo($check['setState']))->willReturn(null);

		ReflectionHelper::setValue($model, 'aliasFields', $test['mock']['alias']);

		$property         = $test['property'];
		$model->$property = $test['value'];

		$count = isset($model->methodCounter[$check['method']]) ? $model->methodCounter[$check['method']] : 0;

		$this->assertEquals($check['count'], $count, sprintf($msg, 'Invoked the specific setter method a wrong amount of times'));
	}
}
