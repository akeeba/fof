<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Event;


use FOF40\Event\Observable;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Event\FirstObserver;

class ObserverTest extends FOFTestCase
{
	/** @var Observable */
	private $dispatcher;

	public static $attachArguments = null;

	public function testConstructor()
	{
		$dummy = new FirstObserver($this->dispatcher);

		$this->assertEquals($dummy, self::$attachArguments);
		$this->assertEquals($this->dispatcher, ReflectionHelper::getValue($dummy, 'subject'));
	}

	public function testGetObservableEvents()
	{
		$dummy = new FirstObserver($this->dispatcher);

		$observableEvents = $dummy->getObservableEvents();

		$this->assertEquals([
			'returnConditional',
			'identifyYourself',
			'chain',
		], $observableEvents);
	}

	protected function setUp()
	{
		$this->dispatcher = $this->getMockBuilder('\\FOF40\\Event\\Observable')
			->disableOriginalConstructor()
			->setMethods(['attach', 'detach', 'trigger'])
			->getMock();

		$this->dispatcher
			->method('attach')
			->will($this->returnCallback(function ($arg) {
				ObserverTest::$attachArguments = $arg;
			}));

		$this->dispatcher
			->method('detach')
			->willReturnSelf();

		$this->dispatcher
			->method('trigger')
			->willReturn([]);
	}
}
