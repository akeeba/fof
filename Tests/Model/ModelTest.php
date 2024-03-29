<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Model;

use FOF40\Input\Input;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\ModelStub;

require_once 'ModelDataprovider.php';

/**
 * @covers      FOF40\Model\Model::<protected>
 * @covers      FOF40\Model\Model::<private>
 * @package     FOF40\Tests\Model
 */
class ModelTest extends FOFTestCase
{
	/**
	 * @group           Model
	 * @group           Model__construct
	 * @covers          FOF40\Model\Model::__construct
	 * @dataProvider    ModelDataprovider::getTest__construct()
	 */
	public function test__construct($test, $check)
	{
		$containerSetup = [
			'componentName' => 'com_fakeapp',
		];

		$msg       = 'Model::__construct %s - Case: ' . $check['case'];
		$container = new TestContainer($containerSetup);

		$model = new ModelStub($container, $test['config']);

		$state    = ReflectionHelper::getValue($model, 'state');
		$populate = ReflectionHelper::getValue($model, '_state_set');
		$ignore   = ReflectionHelper::getValue($model, '_ignoreRequest');
		$name     = ReflectionHelper::getValue($model, 'name');

		$this->assertEquals($check['name'], $name, sprintf($msg, 'Failed to get the correct name'));
		$this->assertEquals($check['state'], $state, sprintf($msg, 'Failed to set the internal state object'));
		$this->assertEquals($check['populate'], $populate, sprintf($msg, 'Failed to set the internal state marker'));
		$this->assertEquals($check['ignore'], $ignore, sprintf($msg, 'Failed to set the internal state marker'));
	}

	/**
	 * @group           Model
	 * @group           ModelGetName
	 * @covers          FOF40\Model\Model::getName
	 */
	public function testGetName()
	{
		$model = new ModelStub(static::$container);

		ReflectionHelper::setValue($model, 'name', null);

		$name = $model->getName();

		$this->assertEquals('ModelStub', $name, 'Model::getName Failed to fetch the correct model name');
	}

	/**
	 * @group           Model
	 * @group           ModelGetTask
	 * @covers          FOF40\Model\Model::getName
	 */
	public function testGetNameException()
	{
		$this->setExpectedException('FOF40\Model\Exception\CannotGetName');

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['getState'])
			->setConstructorArgs([static::$container])
			->getMock();

		ReflectionHelper::setValue($model, 'name', null);

		$model->getName();
	}

	/**
	 * @group           Model
	 * @group           ModelGetState
	 * @covers          FOF40\Model\Model::getState
	 * @dataProvider    ModelDataprovider::getTestGetState
	 */
	public function testGetState($test, $check)
	{
		$msg = 'Model::getState %s - Case: ' . $check['case'];

		$platform                           = static::$container->platform;
		$platform::$getUserStateFromRequest = function () use ($test) {
			return $test['mock']['getUserState'];
		};

		$model = new ModelStub(static::$container, $test['config']);

		ReflectionHelper::setValue($model, '_ignoreRequest', $test['mock']['ignore']);

		$result = $model->getState($test['key'], $test['default'], $test['filter']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
	}

	/**
	 * @group           Model
	 * @group           ModelGetHash
	 * @covers          FOF40\Model\Model::getHash
	 */
	public function testGetHash()
	{
		$model = new ModelStub(static::$container);

		// Sadly I can't test for the internal cache, since the variable is declared as static local, so I can't manipulate it :(
		$hash = $model->getHash();

		$this->assertEquals('Com_fakeapp.nestedset.', $hash, 'Model::getHash returned the wrong value');
	}

	/**
	 * @group           Model
	 * @group           ModelSetState
	 * @covers          FOF40\Model\Model::setState
	 * @dataProvider    ModelDataprovider::getTestSetState
	 */
	public function testSetState($test, $check)
	{
		$msg = 'Model::setState %s - Case: ' . $check['case'];

		$model = new ModelStub(static::$container);

		ReflectionHelper::setValue($model, 'state', $test['mock']['state']);

		$result = $model->setState($test['property'], $test['value']);

		$state = ReflectionHelper::getValue($model, 'state');

		$this->assertEquals($check['state'], $state, sprintf($msg, 'Failed to set the property'));
		$this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong value'));
	}

	/**
	 * @group           Model
	 * @group           ModelClearState
	 * @covers          FOF40\Model\Model::clearState
	 */
	public function testClearState()
	{
		$model = new ModelStub(static::$container);
		ReflectionHelper::setValue($model, 'state', (object) ['foo' => 'bar']);

		$result = $model->clearState();

		// Let's convert the object to an array, so I can assert that is empty
		$state = (array) ReflectionHelper::getValue($model, 'state');

		$this->assertInstanceOf('\\FOF40\\Model\\Model', $result, 'Model::clearState should return an instance of itself');
		$this->assertEmpty($state, 'Model::clearState failed to clear the internal state');
	}

	/**
	 * @group           Model
	 * @group           ModelGetClone
	 * @covers          FOF40\Model\Model::getClone
	 */
	public function testGetClone()
	{
		$model = new ModelStub(static::$container);
		$clone = $model->getClone();

		$this->assertNotSame($model, $clone, 'Model::getClone failed to clone the current instance');
	}

	/**
	 * @group           Model
	 * @group           ModelGetContainer
	 * @covers          FOF40\Model\Model::getContainer
	 */
	public function testGetContainer()
	{
		$model = new ModelStub(static::$container);

		$container = $model->getContainer();

		$this->assertSame(static::$container, $container, 'Model::getContainer Failed to return the same container');
	}

	/**
	 * @group           Model
	 * @group           Model__get
	 * @covers          FOF40\Model\Model::__get
	 */
	public function test__get()
	{
		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['getState'])
			->setConstructorArgs([static::$container])
			->getMock();
		$model->expects($this->once())->method('getState')->with($this->equalTo('foo'))->willReturn('bar');

		$result = $model->foo;

		$this->assertEquals('bar', $result, 'Model::__get Returned the wrong value');
	}

	/**
	 * @group           Model
	 * @group           Model__get
	 * @covers          FOF40\Model\Model::__get
	 */
	public function test__getInput()
	{
		$input     = new Input();
		$container = new TestContainer([
			'componentName' => 'com_fakeapp',
			'input'         => $input,
		]);

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['getState'])
			->setConstructorArgs([$container])
			->getMock();
		$model->expects($this->never())->method('getState');

		$result = $model->input;

		$this->assertSame($input, $result, 'Model::__get Returned the wrong value when asking for the input object');
	}

	/**
	 * @group           Model
	 * @group           Model__set
	 * @covers          FOF40\Model\Model::__set
	 */
	public function test__set()
	{
		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['setState'])
			->setConstructorArgs([static::$container])
			->getMock();
		$model->expects($this->once())->method('setState')->with($this->equalTo('foo'), $this->equalTo('bar'));

		$result = $model->foo = 'bar';

		$this->assertEquals('bar', $result, 'Model::__set Returned the wrong value');
	}

	/**
	 * @group           Model
	 * @group           Model__call
	 * @covers          FOF40\Model\Model::__call
	 */
	public function test__call()
	{
		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['setState'])
			->setConstructorArgs([static::$container])
			->getMock();
		$model->expects($this->once())->method('setState')->with($this->equalTo('foo'), $this->equalTo('bar'));

		$result = $model->foo('bar');

		$this->assertInstanceOf('\\FOF40\\Model\\Model', $result, 'Model::__call should return an istance of itself');
	}

	/**
	 * @group           Model
	 * @group           ModelSavestate
	 * @covers          FOF40\Model\Model::savestate
	 * @dataProvider    ModelDataprovider::getTestSavestate
	 */
	public function testSaveState($test, $check)
	{
		$msg   = 'Model::savestate %s - Case: ' . $check['case'];
		$model = new ModelStub(static::$container);

		$result = $model->savestate($test['state']);
		$state  = ReflectionHelper::getValue($model, '_savestate');

		$this->assertInstanceOf('\\FOF40\\Model\\Model', $result, sprintf($msg, 'Should return an instance of itself'));
		$this->assertSame($check['state'], $state, sprintf($msg, 'Failed to set the savestate'));
	}

	/**
	 * @group           Model
	 * @group           ModelPopulateSavestate
	 * @covers          FOF40\Model\Model::populateSavestate
	 * @dataProvider    ModelDataprovider::getTestPopulatesavestate
	 */
	public function testPopulateSavestate($test, $check)
	{
		$container = new TestContainer([
			'componentName' => 'com_fakeapp',
			'input'         => new Input([
				'savestate' => $test['state'],
			]),
		]);

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\ModelStub')
			->setMethods(['savestate'])
			->setConstructorArgs([$container])
			->getMock();

		$matcher = $this->never();

		if ($check['savestate'])
		{
			$matcher = $this->once();
		}

		$model->expects($matcher)->method('savestate')->with($this->equalTo($check['state']));

		ReflectionHelper::setValue($model, '_savestate', $test['mock']['state']);

		$model->populateSavestate();
	}

	/**
	 * @group       Model
	 * @group       ModelSetIgnoreRequest
	 * @covers      FOF40\Model\Model::setIgnoreRequest
	 */
	public function testSetIgnoreRequest()
	{
		$model = new ModelStub(static::$container);

		$result = $model->setIgnoreRequest(true);

		$ignore = ReflectionHelper::getValue($model, '_ignoreRequest');

		$this->assertInstanceOf('\\FOF40\\Model\\Model', $result, 'Model::setIgnoreRequest should return an instance of itself');
		$this->assertEquals(true, $ignore, 'Model::setIgnoreRequest failed to set the flag');
	}

	/**
	 * @group       Model
	 * @group       ModelGetIgnoreRequest
	 * @covers      FOF40\Model\Model::getIgnoreRequest
	 */
	public function testGetIgnoreRequest()
	{
		$model = new ModelStub(static::$container);

		ReflectionHelper::setValue($model, '_ignoreRequest', 'foobar');

		$result = $model->getIgnoreRequest();

		$this->assertEquals('foobar', $result, 'Model::getIgnoreRequest returned the wrong value');
	}
}
