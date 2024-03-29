<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\AbstractFilter;

use FOF40\Model\DataModel\Filter\AbstractFilter;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModel\Filter\FilterStub;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'AbstractFilterDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Filter\AbstractFilter::<protected>
 * @covers      FOF40\Model\DataModel\Filter\AbstractFilter::<private>
 * @package     FOF40\Tests\DataModel\Filter\AbstractFilter
 */
class AbstractFilterTest extends DatabaseTest
{
	/**
	 * @group       AbstractFilter
	 * @group       AbstractFilterConstruct
	 * @covers      FOF40\Model\DataModel\Filter\AbstractFilter::__construct
	 */
	public function test__construct()
	{
		$db    = JoomlaFactory::getDbo();
		$field = (object) [
			'name' => 'test',
			'type' => 'test',
		];

		$filter = new FilterStub($db, $field);

		$this->assertEquals('test', ReflectionHelper::getValue($filter, 'name'), 'AbstractFilter::__construct Failed to set the field name');
		$this->assertEquals('test', ReflectionHelper::getValue($filter, 'type'), 'AbstractFilter::__construct Failed to set the field type');
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterConstruct
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::__construct
	 * @dataProvider    AbstractFilterDataprovider::getTest__constructException
	 */
	public function test__constructException($test)
	{
		$this->setExpectedException('InvalidArgumentException');

		$db = JoomlaFactory::getDbo();

		new FilterStub($db, $test['field']);
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterIsEmpty
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::isEmpty
	 * @dataProvider    AbstractFilterDataprovider::getTestIsEmpty
	 */
	public function testIsEmpty($test, $check)
	{
		$msg = 'AbstractFilter::isEmpty %s - Case: ' . $check['case'];

		$filter             = new FilterStub(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'test']);
		$filter->null_value = $test['null'];

		$result = $filter->isEmpty($test['value']);

		$this->assertSame($check['result'], $result, sprintf($msg, 'Failed to return the correct value'));
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterSearchMethods
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::getSearchMethods
	 */
	public function testGetSearchMethod()
	{
		$filter = new FilterStub(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'test']);

		$result = $filter->getSearchMethods();
		$result = array_values($result);

		$check = ['between', 'exact', 'partial', 'outside', 'interval', 'search', 'modulo', 'range'];

		sort($result);
		sort($check);

		$this->assertEquals($check, $result, 'AbstractFilter::getSearchMethods Failed to detect the correct methods');
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterExact
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::exact
	 * @dataProvider    AbstractFilterDataprovider::getTestExact
	 */
	public function testExact($test, $check)
	{
		$msg = 'AbstractFilter::exact %s - Case: ' . $check['case'];

		$field = (object) ['name' => 'test', 'type' => 'varchar'];

		$filter = $this->getMockBuilder('\FOF40\Tests\Stubs\Model\DataModel\Filter\FilterStub')
			->setMethods(['isEmpty', 'getFieldName', 'search'])
			->setConstructorArgs([JoomlaFactory::getDbo(), $field])
			->getMock();

		$filter->method('isEmpty')->willReturn($test['mock']['isEmpty']);
		$filter->expects($check['name'] ? $this->once() : $this->never())->method('getFieldName')->willReturn('`test`');
		$filter->expects($check['search'] ? $this->once() : $this->never())->method('search')->willReturn('search');

		$result = $filter->exact($test['value']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Return the wrong value'));
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterSearch
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::search
	 * @dataProvider    AbstractFilterDataprovider::getTestSearch
	 */
	public function testSearch($test, $check)
	{
		$msg = 'AbstractFilter::search %s - Case: ' . $check['case'];

		$field = (object) ['name' => 'test', 'type' => 'varchar'];

		$filter = $this->getMockBuilder('\FOF40\Tests\Stubs\Model\DataModel\Filter\FilterStub')
			->setMethods(['isEmpty', 'getFieldName'])
			->setConstructorArgs([JoomlaFactory::getDbo(), $field])
			->getMock();

		$filter->method('isEmpty')->willReturn($test['mock']['isEmpty']);
		$filter->method('getFieldName')->willReturn('`test`');

		$result = $filter->search($test['value'], $test['operator']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Return the wrong value'));
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterGetFieldName
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::getFieldName
	 */
	public function testGetFieldName()
	{
		$filter = new FilterStub(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'test']);

		$result = $filter->getFieldName();

		$this->assertEquals('`test`', $result, 'AbstractFilter::getFieldName Failed to return the correct field name');
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterGetField
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::getField
	 */
	public function testGetField()
	{
		$field = (object) ['name' => 'test', 'type' => 'int (10)'];

		$result = AbstractFilter::getField($field, ['dbo' => JoomlaFactory::getDbo()]);

		$this->assertInstanceOf('\FOF40\Model\DataModel\Filter\AbstractFilter', $result, 'AbstractFilter::getField Failed to return the correct filter');
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterGetField
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::getField
	 * @dataProvider    AbstractFilterDataprovider::getTestGetFieldException
	 */
	public function testGetFieldException($test)
	{
		$this->setExpectedException('InvalidArgumentException');

		AbstractFilter::getField($test['field'], []);
	}

	/**
	 * @group           AbstractFilter
	 * @group           AbstractFilterGetFieldType
	 * @covers          FOF40\Model\DataModel\Filter\AbstractFilter::getFieldType
	 * @dataProvider    AbstractFilterDataprovider::getTestGetFieldType
	 */
	public function testGetFieldType($test, $check)
	{
		$msg = 'AbstractFilter::getFieldType %s - Case: ' . $check['case'];

		$result = AbstractFilter::getFieldType($test['type']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to get the correct field type'));
	}
}
