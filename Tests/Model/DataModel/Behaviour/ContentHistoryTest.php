<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\ContentHistory;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once 'ContentHistoryDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\ContentHistory::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\ContentHistory::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\ContentHistory
 */
class ContentHistoryTest extends DatabaseTest
{
	protected function tearDown()
	{
		parent::tearDown();

		ReflectionHelper::setValue('\Joomla\CMS\Component\ComponentHelper', 'components', []);
	}

	/**
	 * @group           Behaviour
	 * @group           ContentHistoryOnAfterSave
	 * @covers          FOF40\Model\DataModel\Behaviour\ContentHistory::onAfterSave
	 * @dataProvider    ContentHistoryDataprovider::getTestOnAfterSave
	 */
	public function testOnAfterSave($test, $check)
	{
		$msg     = 'ContentHistory::onAfterSave %s - Case: ' . $check['case'];
		$counter = 0;

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['getContentType', 'checkContentType'])
			->setConstructorArgs([static::$container, $config])
			->getMock();
		$model->method('getContentType')->willReturn('com_foftest');

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new ContentHistory($dispatcher);

		$fakeHelper = new ClosureHelper([
			'store' => function () use (&$counter) {
				$counter++;
			},
		]);

		ReflectionHelper::setValue($behavior, 'historyHelper', $fakeHelper);
		$model->getContainer()->params->set('save_history', $test['save_history']);

		$behavior->onAfterSave($model);

		$this->assertEquals($check['store'], $counter, sprintf($msg, 'Failed to correctly invoke the Content History helper'));
	}

	/**
	 * @group           Behaviour
	 * @group           ContentHistoryOnBeforeDelete
	 * @covers          FOF40\Model\DataModel\Behaviour\ContentHistory::onBeforeDelete
	 * @dataProvider    ContentHistoryDataprovider::getTestOnBeforeDelete
	 */
	public function testOnBeforeDelete($test, $check)
	{
		$msg     = 'ContentHistory::onBeforeDelete %s - Case: ' . $check['case'];
		$counter = 0;

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['getContentType', 'checkContentType'])
			->setConstructorArgs([static::$container, $config])
			->getMock();
		$model->method('getContentType')->willReturn('com_foftest');

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new ContentHistory($dispatcher);

		$fakeHelper = new ClosureHelper([
			'deleteHistory' => function () use (&$counter) {
				$counter++;
			},
		]);

		ReflectionHelper::setValue($behavior, 'historyHelper', $fakeHelper);
		$model->getContainer()->params->set('save_history', $test['save_history']);

		$behavior->onBeforeDelete($model, 1);

		$this->assertEquals($check['delete'], $counter, sprintf($msg, 'Failed to correctly invoke the Content History helper'));
	}

	/**
	 * @group           Behaviour
	 * @group           ContentHistoryOnAfterPublish
	 * @covers          FOF40\Model\DataModel\Behaviour\ContentHistory::onAfterPublish
	 */
	public function testOnAfterPublish()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['updateUcmContent'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($this->once())->method('updateUcmContent');

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new ContentHistory($dispatcher);

		$behavior->onAfterPublish($model);
	}

	/**
	 * @group           Behaviour
	 * @group           ContentHistoryOnAfterUnpublish
	 * @covers          FOF40\Model\DataModel\Behaviour\ContentHistory::onAfterUnpublish
	 */
	public function testOnAfterUnpublish()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['updateUcmContent'])
			->setConstructorArgs([static::$container, $config])
			->getMock();
		$model->expects($this->once())->method('updateUcmContent');

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new ContentHistory($dispatcher);

		$behavior->onAfterUnpublish($model);
	}
}
