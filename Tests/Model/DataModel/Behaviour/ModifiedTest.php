<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Modified;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\User\User;

require_once 'ModifiedDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Modified::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Modified::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Modified
 */
class ModifiedTest extends DatabaseTest
{
	/**
	 * @group           Behaviour
	 * @group           ModifiedOnBeforeCheck
	 * @covers          FOF40\Model\DataModel\Behaviour\Modified::onBeforeCheck
	 */
	public function testOnBeforeCheck()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$platform            = static::$container->platform;
		$platform::$user     = new User(99);
		$platform::$user->id = 99;

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['addSkipCheckField'])
			->setConstructorArgs([static::$container, $config])
			->getMock();
		$model->expects($this->exactly(2))->method('addSkipCheckField')
			->withConsecutive(
				[$this->equalTo('modified_on')],
				[$this->equalTo('modified_by')]
			);

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Modified($dispatcher);

		$behavior->onBeforeCheck($model);
	}

	/**
	 * @group           Behaviour
	 * @group           ModifiedOnBeforeUpdate
	 * @covers          FOF40\Model\DataModel\Behaviour\Modified::onBeforeUpdate
	 * @dataProvider    ModifiedDataprovider::getTestOnBeforeUpdate
	 */
	public function testOnBeforeCreate($test, $check)
	{
		$msg = 'Modified::onAfterBuildQuery %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
			'aliasFields' => $test['aliases'],
		];

		$platform            = static::$container->platform;
		$platform::$user     = new User(99);
		$platform::$user->id = 99;

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['isLocked'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->method('isLocked')->willReturn($test['locked']);
		$dataObject = new \stdClass();

		// Let's set some values inside the model
		$model->setFieldValue('modified_on', $test['modified_on']);
		$model->setFieldValue('modified_by', $test['modified_by']);

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Modified($dispatcher);

		$before_on = $model->getFieldValue('modified_on');
		$before_by = $model->getFieldValue('modified_by');

		$behavior->onBeforeUpdate($model, $dataObject);

		$modified_on = $model->getFieldAlias('modified_on');
		$modified_by = $model->getFieldAlias('modified_by');

		if ($check['modified_on'])
		{
			// First of all let's check that the model and the dataobject have the same data
			$this->assertEquals($model->getFieldValue('modified_on'), $dataObject->$modified_on, sprintf($msg, 'Model and dataobject values are different for modified_on'));

			// Of course I can't check the exact value of the timestamp, so I'll just check if they are not empty
			$this->assertNotEmpty($model->getFieldValue('modified_on'), sprintf($msg, 'Saved the wrong value of modified_on inside the model'));
			$this->assertNotEmpty($dataObject->$modified_on, sprintf($msg, 'Saved the wrong value of modified_on inside the dataobject'));

			$this->assertNotEquals('0000-00-00 00:00:00', $model->getFieldValue('modified_on'), sprintf($msg, 'Saved the wrong value of modified_on inside the model'));
			$this->assertNotEquals('0000-00-00 00:00:00', $dataObject->$modified_on, sprintf($msg, 'Saved the wrong value of modified_on inside the dataobject'));
		}
		else
		{
			$this->assertEquals($before_on, $model->getFieldValue('modified_on'));
			$this->assertObjectNotHasAttribute($modified_on, $dataObject, sprintf($msg, 'The dataobject should not have the modified_on field'));
		}

		if ($check['modified_by'])
		{
			// First of all let's check that the model and the dataobject have the same data
			$this->assertEquals($model->getFieldValue('modified_by'), $dataObject->$modified_by, sprintf($msg, 'Model and dataobject values are different for modified_by'));

			$this->assertEquals($check['modified_by'], $model->getFieldValue('modified_by'), sprintf($msg, 'Saved the wrong value of modified_by inside the model'));
			$this->assertEquals($check['modified_by'], $dataObject->$modified_by, sprintf($msg, 'Saved the wrong value of modified_by inside the dataobject'));
		}
		else
		{
			$this->assertEquals($before_by, $model->getFieldValue('modified_by'));
			$this->assertObjectNotHasAttribute($modified_by, $dataObject, sprintf($msg, 'The dataobject should not have the modified_by field'));
		}
	}
}
