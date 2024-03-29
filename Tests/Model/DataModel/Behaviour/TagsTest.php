<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Input\Input;
use FOF40\Model\DataModel\Behaviour\Tags;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once 'TagsDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Tags::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Tags::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Tags
 */
class TagsTest extends DatabaseTest
{
	/**
	 * @group           Behaviour
	 * @group           TagsOnBeforeCreate
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onBeforeCreate
	 */
	public function testOnBeforeCreate()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model      = new DataModelStub(static::$container, $config);
		$dispatcher = $model->getBehavioursDispatcher();
		$tags       = new Tags($dispatcher);
		$data       = (object) ['tags' => 'foobar'];

		$tags->onBeforeCreate($model, $data);

		$this->assertObjectNotHasAttribute('tags', $data);
	}

	/**
	 * @group           Behaviour
	 * @group           TagsOnBeforeUpdate
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onBeforeUpdate
	 */
	public function testOnBeforeUpdate()
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model      = new DataModelStub(static::$container, $config);
		$dispatcher = $model->getBehavioursDispatcher();
		$tags       = new Tags($dispatcher);
		$data       = (object) ['tags' => 'foobar'];

		$tags->onBeforeCreate($model, $data);

		$this->assertObjectNotHasAttribute('tags', $data);
	}

	/**
	 * @group           Behaviour
	 * @group           TagsOnAfterSave
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onAfterSave
	 * @dataProvider    TagsDataprovider::getTestOnAfterSave
	 */
	public function testOnAfterSave($test, $check)
	{
		if ($check['exception'])
		{
			$this->setExpectedException('Exception');
		}

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$container = new TestContainer([
			'componentName' => 'com_fakeapp',
			'input'         => new Input($test['input']),
		]);

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getContentType', 'checkContentType'])
			->setConstructorArgs([$container, $config])
			->getMock();

		$model->expects($this->exactly($check['contentType']))->method('getContentType')->willReturn('com_foftest.foobars');
		$model->expects($check['checkContent'] ? $this->once() : $this->never())->method('checkContentType');

		$model->addKnownField('tags');
		$model->tags = $test['tags'];

		$fakeHelper = new ClosureHelper([
			'getTagIds'        => function () {
				return 99;
			},
			'postStoreProcess' => function () use ($test) {
				return $test['mock']['postProc'];
			},
		]);

		$dispatcher = $model->getBehavioursDispatcher();
		$tags       = new Tags($dispatcher);

		ReflectionHelper::setValue($tags, 'tagsHelper', $fakeHelper);

		$tags->onAfterSave($model);

		// We must have an assertion inside our tests, otherwise phpUnit will complain when running with the strict flag
		$this->assertTrue(true);
	}


	/**
	 * @group           Behaviour
	 * @group           TagsOnAfterDelete
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onAfterDelete
	 * @dataProvider    TagsDataprovider::getTestOnAfterDelete
	 */
	public function testOnAfterDelete($test, $check)
	{
		if ($check['exception'])
		{
			$this->setExpectedException('Exception');
		}

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getContentType'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->method('getContentType')->willReturn('com_foftest.foobars');

		$fakeHelper = new ClosureHelper([
			'deleteTagData' => function () use ($test) {
				return $test['mock']['delete'];
			},
		]);

		$dispatcher = $model->getBehavioursDispatcher();
		$tags       = new Tags($dispatcher);

		ReflectionHelper::setValue($tags, 'tagsHelper', $fakeHelper);

		$tags->onAfterDelete($model, 1);

		// We must have an assertion inside our tests, otherwise phpUnit will complain when running with the strict flag
		$this->assertTrue(true);
	}

	/**
	 * @group           Behaviour
	 * @group           TagsOnAfterBind
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onAfterBind
	 * @dataProvider    TagsDataprovider::getTestOnAfterBind
	 */
	public function testOnAfterBind($test, $check)
	{
		$msg = 'Tags::onAfterBind %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		// Let's mock the user state getting, since I don't have any valid application
		$platform                           = static::$container->platform;
		$platform::$getUserStateFromRequest = function () {
			return null;
		};

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getContentType', 'addKnownField'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['contentType'] ? $this->once() : $this->never())->method('getContentType')->willReturn('com_foftest.foobars');
		$model->expects($check['addKnown'] ? $this->once() : $this->never())->method('addKnownField');

		if ($test['load'])
		{
			$model->find($test['load']);
		}

		if ($test['tags'])
		{
			$model->tags = $test['tags'];
		}

		$fakeHelper = new ClosureHelper([
			'getTagIds' => function () {
				return 99;
			},
		]);

		$dispatcher = $model->getBehavioursDispatcher();
		$tags       = new Tags($dispatcher);
		$dummy      = [];

		ReflectionHelper::setValue($tags, 'tagsHelper', $fakeHelper);

		$tags->onAfterBind($model, $dummy);

		$this->assertEquals($check['result'], $model->tags, sprintf($msg, 'Returned the wrong value for the tags'));
	}

	/**
	 * @group           Behaviour
	 * @group           TagsOnAfterPublish
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onAfterPublish
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
		$tags       = new Tags($dispatcher);

		$tags->onAfterPublish($model);
	}

	/**
	 * @group           Behaviour
	 * @group           TagsOnAfterUnpublish
	 * @covers          FOF40\Model\DataModel\Behaviour\Tags::onAfterUnpublish
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
		$tags       = new Tags($dispatcher);

		$tags->onAfterUnpublish($model);
	}
}
