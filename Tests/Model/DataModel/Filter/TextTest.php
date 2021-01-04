<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\DataModel\Text;

use FOF40\Model\DataModel\Filter\Text;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'TextDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Filter\Text::<protected>
 * @covers      FOF40\Model\DataModel\Filter\Text::<private>
 * @package     FOF40\Tests\DataModel\Filter\Text
 */
class TextTest extends DatabaseTest
{
	/**
	 * @group       TextFilter
	 * @group       TextFilterConstruct
	 * @covers      FOF40\Model\DataModel\Filter\Text::__construct
	 */
	public function test__construct()
	{
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$null_value = $filter->null_value;

		$this->assertSame('', $null_value, 'Text::__construct should set the null value to an empty string');
	}

	/**
	 * @group           TextFilter
	 * @group           TextFilterPartial
	 * @covers          FOF40\Model\DataModel\Filter\Text::partial
	 * @dataProvider    TextDataprovider::getTestPartial
	 */
	public function testPartial($test, $check)
	{
		$msg    = 'Text::partial %s - Case: ' . $check['case'];
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$result = $filter->partial($test['value']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
	}

	/**
	 * @group           TextFilter
	 * @group           TextFilterExact
	 * @covers          FOF40\Model\DataModel\Filter\Text::exact
	 * @dataProvider    TextDataprovider::getTestExact
	 */
	public function testExact($test, $check)
	{
		$msg    = 'Text::exact %s - Case: ' . $check['case'];
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$result = $filter->exact($test['value']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to build the correct SQL query'));
	}

	/**
	 * @group           TextFilter
	 * @group           TextFilterBetween
	 * @covers          FOF40\Model\DataModel\Filter\Text::between
	 */
	public function testBetween()
	{
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$this->assertSame('', $filter->between('', ''), 'Text::between Should return an empty string');
	}

	/**
	 * @group           TextFilter
	 * @group           TextFilterOutside
	 * @covers          FOF40\Model\DataModel\Filter\Text::outside
	 */
	public function testOutside()
	{
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$this->assertSame('', $filter->outside('', ''), 'Text::outside Should return an empty string');
	}

	/**
	 * @group           TextFilter
	 * @group           TextFilterInterval
	 * @covers          FOF40\Model\DataModel\Filter\Text::interval
	 */
	public function testInterval()
	{
		$filter = new Text(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'varchar(10)']);

		$this->assertSame('', $filter->interval('', ''), 'Text::interval Should return an empty string');
	}
}
