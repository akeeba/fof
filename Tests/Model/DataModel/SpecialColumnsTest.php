<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ObserverClosure;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\DataModelStub;
use Joomla\CMS\Date\Date as JoomlaDate;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\User\User;

require_once 'SpecialColumnsDataprovider.php';

/**
 * @covers      \FOF40\Model\DataModel::<protected>
 * @covers      \FOF40\Model\DataModel::<private>
 * @package     FOF40\Tests\DataModel
 */
class DataModelSpecialColumnsTest extends DatabaseTest
{
	/**
	 * @group           DataModel
	 * @group           DataModelReorder
	 * @covers          \FOF40\Model\DataModel::reorder
	 * @dataProvider    \SpecialColumnsDataprovider::getTestReorder
	 */
	public function testReorder($test, $check)
	{
		// Please note that if you try to debug this test, you'll get a "Couldn't fetch mysqli_result" error
		// That's harmless and appears in debug only, you might want to suppress exception throwing
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$before = 0;
		$after  = 0;
		$db     = JoomlaFactory::getDbo();
		$msg    = 'DataModel::reorder %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeReorder' => function () use (&$before) {
				$before++;
			},
			'onAfterReorder'  => function () use (&$after) {
				$after++;
			},
		];

		// Let's mess up the records a little
		foreach ($test['mock']['ordering'] as $id => $order)
		{
			$query = $db->getQuery(true)
				->update($db->qn('#__foftest_foobars'))
				->set($db->qn('ordering') . ' = ' . $db->q($order))
				->where($db->qn('foftest_foobar_id') . ' = ' . $db->q($id));

			$db->setQuery($query)->execute();
		}

		$model = new DataModelStub(static::$container, $config, $methods);

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([static::$container])
			->getMock();

		$dispatcher->expects($this->exactly(2))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeReorder')],
			[$this->equalTo('onAfterReorder')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		$result = $model->reorder($test['where']);

		// Now let's take a look at the updated records
		$query    = $db->getQuery(true)
			->select('ordering')
			->from($db->qn('#__foftest_foobars'))
			->order($db->qn($model->getIdFieldName()) . ' ASC');
		$ordering = $db->setQuery($query)->loadColumn();

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals(1, $before, sprintf($msg, 'Failed to invoke the onBefore method'));
		$this->assertEquals(1, $after, sprintf($msg, 'Failed to invoke the onAfter method'));
		$this->assertEquals($check['order'], $ordering, sprintf($msg, 'Failed to save the correct order'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelReorder
	 * @covers          \FOF40\Model\DataModel::reorder
	 */
	public function testReorderException()
	{
		$this->setExpectedException('\\FOF40\\Model\\DataModel\\Exception\\SpecialColumnMissing');

		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$model = new DataModelStub(static::$container, $config);
		$model->reorder();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelMove
	 * @covers          \FOF40\Model\DataModel::move
	 * @dataProvider    \SpecialColumnsDataprovider::getTestMove
	 */
	public function testMove($test, $check)
	{
		// Please note that if you try to debug this test, you'll get a "Couldn't fetch mysqli_result" error
		// That's harmless and appears in debug only, you might want to suppress exception thowing
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$before     = 0;
		$beforeDisp = 0;
		$after      = 0;
		$afterDisp  = 0;
		$db         = JoomlaFactory::getDbo();
		$msg        = 'DataModel::move %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeMove' => function () use (&$before) {
				$before++;
			},
			'onAfterMove'  => function () use (&$after) {
				$after++;
			},
		];

		$model      = new DataModelStub(static::$container, $config, $methods);
		$dispatcher = $model->getBehavioursDispatcher();

		// Let's attach a custom observer, so I can mock and check all the calls performed by the dispatcher
		// P.A. The object is immediatly attached to the dispatcher, so I don't need to manually do that
		new ObserverClosure($dispatcher, [
			'onBeforeMove' => function (&$subject, &$delta, &$where) use ($test, &$beforeDisp) {
				if (!is_null($test['mock']['find']))
				{
					$subject->find($test['mock']['find']);
				}

				if (!is_null($test['mock']['delta']))
				{
					$delta = $test['mock']['delta'];
				}

				if (!is_null($test['mock']['where']))
				{
					$where = $test['mock']['where'];
				}

				$beforeDisp++;
			},
			'onAfterMove'  => function () use (&$afterDisp) {
				$afterDisp++;
			},
		]);

		if ($test['id'])
		{
			$model->find($test['id']);
		}

		$result = $model->move($test['delta'], $test['where']);

		// Now let's take a look at the updated records
		$query    = $db->getQuery(true)
			->select('ordering')
			->from($db->qn('#__foftest_foobars'))
			->order($db->qn($model->getIdFieldName()) . ' ASC');
		$ordering = $db->setQuery($query)->loadColumn();

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals(1, $before, sprintf($msg, 'Failed to invoke the onBefore method'));
		$this->assertEquals(1, $beforeDisp, sprintf($msg, 'Failed to invoke the onBeforeMove event'));
		$this->assertEquals(1, $after, sprintf($msg, 'Failed to invoke the onAfter method'));
		$this->assertEquals(1, $afterDisp, sprintf($msg, 'Failed to invoke the onAfterMove event'));
		$this->assertEquals($check['order'], $ordering, sprintf($msg, 'Failed to save the correct order'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelMove
	 * @covers          \FOF40\Model\DataModel::move
	 * @dataProvider    \SpecialColumnsDataprovider::getTestMoveException
	 */
	public function testMoveException($test, $check)
	{
		$this->setExpectedException($check['exception']);

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model = new DataModelStub(static::$container, $config);
		$model->move(-1);
	}

	/**
	 * @group           DataModel
	 * @group           DataModelLock
	 * @covers          \FOF40\Model\DataModel::lock
	 * @dataProvider    \SpecialColumnsDataprovider::getTestLock
	 */
	public function testLock($test, $check)
	{
		$before = 0;
		$after  = 0;
		$msg    = 'DataModel::lock %s - Case: ' . $check['case'];

		$container           = new TestContainer();
		$platform            = $container->platform;
		$platform::$user     = new User($test['mock']['user_id']);
		$platform::$user->id = $test['mock']['user_id'];


		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeLock' => function () use (&$before) {
				$before++;
			},
			'onAfterLock'  => function () use (&$after) {
				$after++;
			},
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save', 'getId'])
			->setConstructorArgs([$container, $config, $methods])
			->getMock();

		$model->method('getId')->willReturn(1);

		// Let's mock the dispatcher, too. So I can check if events are really triggered
		$dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Dispatcher')
			->setMethods(['trigger'])
			->setConstructorArgs([$container])
			->getMock();

		$dispatcher->expects($this->exactly($check['dispatcher']))->method('trigger')->withConsecutive(
			[$this->equalTo('onBeforeLock')],
			[$this->equalTo('onAfterLock')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		/** @var DataModel $model */
		$result = $model->lock($test['user_id']);

		$locked_by = $model->getFieldValue('locked_by');
		$locked_on = $model->getFieldValue('locked_on');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertEquals($check['locked_by'], $locked_by, sprintf($msg, 'Failed to set the locking user'));

		// The time is calculated on the fly, so I can only check if it's null or not
		if ($check['locked_on'])
		{
			$this->assertNotNull($locked_on, sprintf($msg, 'Failed to set the locking time'));
		}
		else
		{
			$this->assertNull($locked_on, sprintf($msg, 'Failed to set the locking time'));
		}
	}

	/**
	 * @group           DataModel
	 * @group           DataModelLock
	 * @covers          \FOF40\Model\DataModel::lock
	 */
	public function testLockException()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$this->setExpectedException('RuntimeException');

		$model = new DataModelStub(static::$container, $config);
		$model->lock();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelUnlock
	 * @covers          \FOF40\Model\DataModel::unlock
	 * @dataProvider    \SpecialColumnsDataprovider::getTestUnlock
	 */
	public function testUnlock($test, $check)
	{
		$before = 0;
		$after  = 0;
		$db     = JoomlaFactory::getDbo();
		$msg    = 'DataModel::unlock %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		// I am passing those methods so I can double check if the method is really called
		$methods = [
			'onBeforeUnlock' => function () use (&$before) {
				$before++;
			},
			'onAfterUnlock'  => function () use (&$after) {
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
			[$this->equalTo('onBeforeUnlock')],
			[$this->equalTo('onAfterUnlock')]
		);

		ReflectionHelper::setValue($model, 'behavioursDispatcher', $dispatcher);

		if ($model->hasField('locked_on'))
		{
			$now = new JoomlaDate();
			$model->setFieldValue('locked_on', $now->toSql());
		}

		$result = $model->unlock();

		$locked_by = $model->getFieldValue('locked_by');
		$locked_on = $model->getFieldValue('locked_on');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['before'], $before, sprintf($msg, 'Failed to call the onBefore method'));
		$this->assertEquals($check['after'], $after, sprintf($msg, 'Failed to call the onAfter method'));
		$this->assertEquals($check['locked_by'], $locked_by, sprintf($msg, 'Failed to set the locking user'));

		// The time is calculated on the fly, so I can only check if it's null or not
		if ($check['locked_on'] && ($check['zero_date'] ?? true))
		{
			$this->assertEquals($db->getNullDate(), $locked_on, sprintf($msg, 'Failed to set the locking time'));

			return;
		}

		$this->assertNull($locked_on, sprintf($msg, 'Failed to set the locking time'));
	}

	/**
	 * @group           DataModel
	 * @group           DataModelUnlock
	 * @covers          \FOF40\Model\DataModel::unlock
	 */
	public function testUnlockException()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->unlock();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelTouch
	 * @covers          \FOF40\Model\DataModel::touch
	 * @dataProvider    \SpecialColumnsDataprovider::getTestTouch
	 */
	public function testTouch($test, $check)
	{
		$msg = 'DataModel::touch %s - Case: ' . $check['case'];

		$container           = new TestContainer();
		$platform            = $container->platform;
		$platform::$user     = new User($test['mock']['user_id']);
		$platform::$user->id = $test['mock']['user_id'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['save', 'getId'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->method('save')->willReturn(null);
		$model->method('getId')->willReturn(1);

		/** @var DataModel $model */
		$result = $model->touch($test['user_id']);

		$modified_by = $model->getFieldValue('modified_by');
		$modified_on = $model->getFieldValue('modified_on');

		$this->assertInstanceOf('\\FOF40\\Model\\DataModel', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertEquals($check['modified_by'], $modified_by, sprintf($msg, 'Failed to set the modifying user'));

		// The time is calculated on the fly, so I can only check if it's null or not
		if ($check['modified_on'])
		{
			$this->assertNotNull($modified_on, sprintf($msg, 'Failed to set the modifying time'));
		}
		else
		{
			$this->assertNull($modified_on, sprintf($msg, 'Failed to set the modifying time'));
		}
	}

	/**
	 * @group           DataModel
	 * @group           DataModelTouch
	 * @covers          \FOF40\Model\DataModel::touch
	 */
	public function testTouchException()
	{
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$this->setExpectedException('FOF40\Model\DataModel\Exception\RecordNotLoaded');

		$model = new DataModelStub(static::$container, $config);
		$model->touch();
	}

	/**
	 * @group           DataModel
	 * @group           DataModelCheckIn
	 * @covers          \FOF40\Model\DataModel::checkIn
	 * @dataProvider    \SpecialColumnsDataprovider::getTestCheckIn
	 */
	public function testCheckIn($test, $check)
	{
		$container = new TestContainer([
			'platform' => new ClosureHelper([
				'getDbo'    => function () {
					return JoomlaFactory::getDbo();
				},
				'getUser'   => function () {
					return (object) ['id' => 99];
				},
				'authorise' => function ($self, $permission, $section) use ($test) {
					return $test['mock']['permissions'][$section][$permission];
				},
			]),
		]);

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['unlock', 'isAssetsTracked', 'getAssetKey'])
			->setConstructorArgs([$container, $config])
			->getMock();

		$model->expects($check['unlock'] ? $this->once() : $this->never())->method('unlock')->willReturnSelf();
		$model->method('isAssetsTracked')->willReturn($test['mock']['assetsTracked']);
		$model->method('getAssetKey')->willReturn('foobars.dummy');

		if ($check['exception'])
		{
			$this->setExpectedException($check['exception']);
		}

		/** @var DataModel $model */
		if ($test['load'])
		{
			$model->find($test['load']);
		}

		$result = $model->checkIn($test['userid']);

		$this->assertInstanceOf('FOF40\Model\DataModel', $result);
	}

	/**
	 * @group           DataModel
	 * @group           DataModelIsLocked
	 * @covers          \FOF40\Model\DataModel::isLocked
	 * @dataProvider    \SpecialColumnsDataprovider::getTestIsLocked
	 */
	public function testIsLocked($test, $check)
	{
		$msg = 'DataModel::isLocked %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model = new DataModelStub(static::$container, $config);

		if ($test['load'])
		{
			$model->find($test['load']);
		}

		$result = $model->isLocked($test['userid']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
	}
}