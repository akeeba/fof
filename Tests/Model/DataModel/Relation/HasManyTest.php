<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Relation\Relation\HasMany;

use FOF40\Model\DataModel\Relation\HasMany;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once 'HasManyDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Relation\HasMany::<protected>
 * @covers      FOF40\Model\DataModel\Relation\HasMany::<private>
 * @package     FOF40\Tests\DataModel\Relation\HasMany
 */
class HasManyTest extends DatabaseTest
{
	/**
	 * @group           HasMany
	 * @group           HasManyConstruct
	 * @covers          FOF40\Model\DataModel\Relation\HasMany::__construct
	 * @dataProvider    HasManyDataprovider::getTestConstruct
	 */
	public function testConstruct($test, $check)
	{
		$msg      = 'HasMany::__construct %s - Case: ' . $check['case'];
		$model    = $this->buildModel();
		$relation = new HasMany($model, 'Children', $test['local'], $test['foreign']);

		$this->assertEquals($check['local'], ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
		$this->assertEquals($check['foreign'], ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
	}

	/**
	 * @group           HasMany
	 * @group           HasManyGetCountSubquery
	 * @covers          FOF40\Model\DataModel\Relation\HasMany::getCountSubquery
	 */
	public function testGetCountSubquery()
	{
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$model    = $this->buildModel();
		$relation = new HasMany($model, 'Children');

		$query = $relation->getCountSubquery();

		$check  = 'SELECT COUNT(*) FROM `#__fakeapp_children` AS `reltbl` WHERE `reltbl`.`fakeapp_parent_id` = `#__fakeapp_parents`.`fakeapp_parent_id`';
		$actual = (string) $query;
		$actual = str_replace("\r\n", " ", $actual);
		$actual = str_replace("\n", " ", $actual);
		$actual = str_replace("\r", " ", $actual);
		$actual = str_replace("   ", " ", $actual);
		$actual = str_replace("  ", " ", $actual);
		$actual = trim($actual);

		$this->assertEquals($check, $actual, 'HasMany::getCountSubquery Returned the wrong query');
	}

	/**
	 * @group           HasMany
	 * @group           HasManyGetNew
	 * @covers          FOF40\Model\DataModel\Relation\HasMany::getNew
	 */
	public function testGetNew()
	{
		$model = $this->buildModel();
		$model->find(2);
		$relation = new HasMany($model, 'Children');

		$new = $relation->getNew();

		$this->assertInstanceOf('Fakeapp\Site\Model\Children', $new);
		$this->assertEquals(2, $new->getFieldValue('fakeapp_parent_id'), 'HasMany::getNew Failed to prime the new record');
	}

	/**
	 * @param   string $class
	 *
	 * @return \FOF40\Model\DataModel
	 */
	protected function buildModel($class = null)
	{
		if (!$class)
		{
			$class = '\\FOF40\\Tests\\Stubs\\Model\\DataModelStub';
		}

		$config = [
			'idFieldName' => 'fakeapp_parent_id',
			'tableName'   => '#__fakeapp_parents',
		];

		return new $class(static::$container, $config);
	}
}
