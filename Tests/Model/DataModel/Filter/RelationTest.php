<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Filter\Relation;

use FOF40\Model\DataModel\Filter\Relation;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use Joomla\CMS\Factory as JoomlaFactory;

/**
 * @covers      FOF40\Model\DataModel\Filter\Relation::<protected>
 * @covers      FOF40\Model\DataModel\Filter\Relation::<private>
 * @package     FOF40\Tests\DataModel\Filter\Relation
 */
class RelationTest extends DatabaseTest
{
	/**
	 * @group       RelationFilter
	 * @group       RelationFilterConstruct
	 * @covers      FOF40\Model\DataModel\Filter\Relation::__construct
	 */
	public function test__construct()
	{
		$subquery = JoomlaFactory::getDbo()->getQuery(true);
		$subquery->select('*')->from('test');

		$filter = new Relation(JoomlaFactory::getDbo(), 'foo', $subquery);

		$this->assertEquals('foo', ReflectionHelper::getValue($filter, 'name'), 'Relation::__construct Failed to set filter name');
		$this->assertEquals('relation', ReflectionHelper::getValue($filter, 'type'), 'Relation::__construct Failed to set filter type');
		$this->assertEquals($subquery, ReflectionHelper::getValue($filter, 'subQuery'), 'Relation::__construct Failed to set the subQuery field');
	}

	/**
	 * @group       RelationFilter
	 * @group       RelationFilterCallback
	 * @covers      FOF40\Model\DataModel\Filter\Relation::callback
	 */
	public function testCallback()
	{
		$subquery = JoomlaFactory::getDbo()->getQuery(true);
		$subquery->select('*')->from('test');

		$filter = new Relation(JoomlaFactory::getDbo(), 'foo', $subquery);

		$result = $filter->callback(function ($query) {
			$query->where('bar = 1');

			return $query;
		});

		$check = 'SELECT *
FROM test
WHERE bar = 1';

		$this->assertEquals($check, trim((string) $result), 'Relation::callback Returned the wrong result');
	}

	/**
	 * @group       RelationFilter
	 * @group       RelationFilterGetFieldName
	 * @covers      FOF40\Model\DataModel\Filter\Relation::getFieldName
	 */
	public function testGetFieldName()
	{
		$subquery = JoomlaFactory::getDbo()->getQuery(true);
		$subquery->select('*')->from('test');

		$filter = new Relation(JoomlaFactory::getDbo(), 'foo', $subquery);

		$result = $filter->getFieldName();

		$check = '(
SELECT *
FROM test)';

		$this->assertEquals($check, $result, 'Relation::getFieldName Returned the wrong result');
	}
}
