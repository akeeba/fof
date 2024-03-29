<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Date;

use FOF40\Model\DataModel\Filter\Date;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'DateDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Filter\Date::<protected>
 * @covers      FOF40\Model\DataModel\Filter\Date::<private>
 * @package     FOF40\Tests\DataModel\Filter\Date
 */
class DateTest extends DatabaseTest
{
	/**
	 * @covers      FOF40\Model\DataModel\Filter\Date::getDefaultSearchMethod
	 */
	public function testGetDefaultSearchMethod()
	{
		$filter = new Date(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'datetime']);

		$this->assertEquals('exact', $filter->getDefaultSearchMethod());
	}

	/**
	 * @group           DateFilter
	 * @group           DateFilterBetween
	 * @covers          FOF40\Model\DataModel\Filter\Date::between
	 * @dataProvider    DateDataprovider::getTestBetween
	 */
	public function testBetween($test, $check)
	{
		$msg    = 'Date::between %s - Case: ' . $check['case'];
		$filter = new Date(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'datetime']);

		$result = $filter->between($test['from'], $test['to'], $test['include']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
	}

	/**
	 * @group           DateFilter
	 * @group           DateFilterOutside
	 * @covers          FOF40\Model\DataModel\Filter\Date::outside
	 * @dataProvider    DateDataprovider::getTestOutside
	 */
	public function testOutside($test, $check)
	{
		$msg    = 'Date::outside %s - Case: ' . $check['case'];
		$filter = new Date(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'datetime']);

		$result = $filter->outside($test['from'], $test['to'], $test['include']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
	}

	/**
	 * @group           DateFilter
	 * @group           DateFilterInterval
	 * @covers          FOF40\Model\DataModel\Filter\Date::interval
	 * @dataProvider    DateDataprovider::getTestInterval
	 */
	public function testInterval($test, $check)
	{
		$msg    = 'Date::interval %s - Case: ' . $check['case'];
		$filter = new Date(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'datetime']);

		$result = $filter->interval($test['value'], $test['interval'], $test['include']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
	}
}
