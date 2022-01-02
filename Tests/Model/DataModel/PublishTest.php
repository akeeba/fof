<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once 'PublishDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel::<protected>
 * @covers      FOF40\Model\DataModel::<private>
 * @package     FOF40\Tests\DataModel
 */
class DataModelPublishTest extends DatabaseTest
{
	/**
	 * @group           DataModel
	 * @group           DataModelArchive
	 * @covers          FOF40\Model\DataModel::archive
	 * @dataProvider    PublishDataprovider::getTestArchive
	 */
	public function testArchive($test, $check)
	{
		$msg     = 'DataModel::getFieldValue %s - Case: ' . $check['case'];
		$methods = [];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		if ($test['mock']['before'])
		{
			$methods['onBeforeArchive'] = $test['mock']['before'];
		}

		if ($test['mock']['after'])
		{
			$methods['onAfterArchive'] = $test['mock']['after'];
		}

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['save', 'getId'])
			->setConstructorArgs([static::$container, $config, $methods])
			->getMock();
		$model->method('getId')->willReturn(1);
		$model->expects($check['save'] ? $this->once() : $this->never())->method('save');

		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeArchive')],
			[$this->equalTo('onAfterArchive')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);
		ReflectionHelper::setValue($model, 'aliasFields', $test['mock']['alias']);

		if ($check['exception'])
		{
			$this->setExpectedException('Exception');
		}

		$result = $model->archive();

		if ($check['save'])
		{
			$enabled = $model->getFieldAlias('enabled');
			$value   = $model->$enabled;

			$this->assertEquals(2, $value, sprintf($msg, 'Should set the value of the enabled field to 2'));
		}

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an istance of itself'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelArchive
	 * @covers          FOF40\Model\DataModel::archive
	 */
	public function testArchiveException()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->archive();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelTrash
	 * @covers          FOF40\Model\DataModel::trash
	 * @dataProvider    PublishDataprovider::getTestTrash
	 */
	public function testTrash($test, $check)
	{
		$before = 0;
		$after  = 0;
		$msg    = 'DataModel::trash %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeTrash' => function () use (&$before) {
				$before++;
			},
			'onAfterTrash'  => function () use (&$after) {
				$after++;
			},
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save', 'getId', 'findOrFail'])
			->setConstructorArgs([static::$container, $config, $methods])
			->getMock();

		$model->method('getId')->willReturn(1);
		$model->expects($check['find'] ? $this->once() : $this->never())->method('findOrFail')->willReturn(null);

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeTrash')],
			[$this->equalTo('onAfterTrash')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		$result = $model->trash($test['id']);

		$enabled = $model->getFieldValue('enabled');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelTrash
	 * @covers          FOF40\Model\DataModel::trash
	 * @dataProvider    PublishDataprovider::getTestTrashException
	 */
	public function testTrashException($test, $check)
	{
		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$this->setExpectedException($check['exception']);

		$model = new DataModelStub(static::$container, $config);
		$model->trash($test['id']);
	}

	/**
	 * @group           DataModel
	 * @group           DataModelPublish
	 * @covers          FOF40\Model\DataModel::publish
	 * @dataProvider    PublishDataprovider::getTestPublish
	 */
	public function testPublish($test, $check)
	{
		$before = 0;
		$after  = 0;
		$msg    = 'DataModel::publish %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforePublish' => function () use (&$before) {
				$before++;
			},
			'onAfterPublish'  => function () use (&$after) {
				$after++;
			},
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['save', 'getId'])
			->setConstructorArgs([static::$container, $config, $methods])
			->getMock();
		$model->method('getId')->willReturn(1);

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforePublish')],
			[$this->equalTo('onAfterPublish')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		$result = $model->publish($test['state']);

		$enabled = $model->getFieldValue('enabled');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertEquals($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelPublish
	 * @covers          FOF40\Model\DataModel::publish
	 */
	public function testPublishException()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->publish();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelRestore
	 * @covers          FOF40\Model\DataModel::restore
	 * @dataProvider    PublishDataprovider::getTestrestore
	 */
	public function testRestore($test, $check)
	{
		$before = 0;
		$after  = 0;
		$msg    = 'DataModel::restore %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeRestore' => function () use (&$before) {
				$before++;
			},
			'onAfterRestore'  => function () use (&$after) {
				$after++;
			},
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save', 'getId', 'findOrFail'])
			->setConstructorArgs([static::$container, $config, $methods])
			->getMock();

		$model->method('getId')->willReturn(1);
		$model->expects($check['find'] ? $this->once() : $this->never())->method('findOrFail');

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeRestore')],
			[$this->equalTo('onAfterRestore')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		$result = $model->restore($test['id']);

		$enabled = $model->getFieldValue('enabled');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelRestore
	 * @covers          FOF40\Model\DataModel::restore
	 */
	public function testRestoreException()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->restore();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelUnpublish
	 * @covers          FOF40\Model\DataModel::unpublish
	 * @dataProvider    PublishDataprovider::getTestUnpublish
	 */
	public function testUnpublish($test, $check)
	{
		$before = 0;
		$after  = 0;
		$msg    = 'DataModel::unpublish %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeUnpublish' => function () use (&$before) {
				$before++;
			},
			'onAfterUnpublish'  => function () use (&$after) {
				$after++;
			},
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['save', 'getId'])
			->setConstructorArgs([static::$container, $config, $methods])
			->getMock();
		$model->method('getId')->willReturn(1);

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeUnpublish')],
			[$this->equalTo('onAfterUnpublish')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		$result = $model->unpublish();

		$enabled = $model->getFieldValue('enabled');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertSame($check['enabled'], $enabled, sprintf($msg, 'Failed to set the enabled field'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelUnpublish
	 * @covers          FOF40\Model\DataModel::unpublish
	 */
	public function testUnpublishException()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->unpublish();
	}
}
