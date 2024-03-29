<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Number;

use FOF40\Model\DataModel\Filter\Number;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'NumberDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Filter\Number::<protected>
 * @covers      FOF40\Model\DataModel\Filter\Number::<private>
 * @package     FOF40\Tests\DataModel\Filter\Number
 */
class NumberTest extends DatabaseTest
{
	/**
	 * @group       NumberFilter
	 * @group       NumberFilterPartial
	 * @covers      FOF40\Model\DataModel\Filter\Number::partial
	 */
	public function testPartial()
	{
		$field = (object) ['name' => 'test', 'type' => 'int (10)'];

		$filter = $this->getMockBuilder('FOF40\Model\DataModel\Filter\Number')
			->setMethods(['exact'])
			->setConstructorArgs([JoomlaFactory::getDbo(), $field])
			->getMock();

		// Should just invoke "exact"
		$filter->expects($this->once())->method('exact')->willReturn(null);

		$filter->partial(10);
	}

	/**
	 * @group           NumberFilter
	 * @group           NumberFilterEquals
	 * @covers          FOF40\Model\DataModel\Filter\Number::search
	 * @dataProvider    NumberDataprovider::getTestSearch
	 */
	public function testSearch($test, $check)
	{
		$msg    = 'Number::search %s - Case: ' . $check['case'];
		$filter = new Number(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'int (10)']);

		$result = $filter->search($test['value'], $test['operator']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct SQL'));
	}

	/**
	 * @group           NumberFilter
	 * @group           NumberFilterBetween
	 * @covers          FOF40\Model\DataModel\Filter\Number::between
	 * @dataProvider    NumberDataprovider::getTestBetween
	 */
	public function testBetween($test, $check)
	{
		$msg    = 'Number::between %s - Case: ' . $check['case'];
		$filter = new Number(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'int (10)']);

		$result = $filter->between($test['from'], $test['to'], $test['inclusive']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct SQL'));
	}

	/**
	 * @group           NumberFilter
	 * @group           NumberFilterOutside
	 * @covers          FOF40\Model\DataModel\Filter\Number::outside
	 * @dataProvider    NumberDataprovider::getTestOutside
	 */
	public function testOutside($test, $check)
	{
		$msg    = 'Number::outside %s - Case: ' . $check['case'];
		$filter = new Number(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'int (10)']);

		$result = $filter->outside($test['from'], $test['to'], $test['inclusive']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct SQL'));
	}

	/**
	 * @group           NumberFilter
	 * @group           NumberFilterInterval
	 * @covers          FOF40\Model\DataModel\Filter\Number::interval
	 * @dataProvider    NumberDataprovider::getTestInterval
	 */
	public function testInterval($test, $check)
	{
		$msg    = 'Number::interval %s - Case: ' . $check['case'];
		$filter = new Number(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'int (10)']);

		$result = $filter->interval($test['value'], $test['interval'], $test['inclusive']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to return the correct SQL'));
	}
}
