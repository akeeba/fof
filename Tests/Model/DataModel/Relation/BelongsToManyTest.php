<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Relation\Relation\BelongsToMany;

use Fakeapp\Site\Model\Groups;
use Fakeapp\Site\Model\Parts;
use FOF40\Model\DataModel\Collection;
use FOF40\Model\DataModel\Relation\BelongsToMany;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\User\User;

require_once 'BelongsToManyDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Relation\BelongsToMany::<protected>
 * @covers      FOF40\Model\DataModel\Relation\BelongsToMany::<private>
 * @package     FOF40\Tests\DataModel\Relation\BelongsToMany
 */
class BelongsToManyTest extends DatabaseTest
{
	/**
	 * @group           BelongsToMany
	 * @group           BelongsToManyContruct
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::__construct
	 * @dataProvider    BelongsToManyDataprovider::getTestConstruct
	 */
	public function testConstruct($test, $check)
	{
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$msg = 'BelongsToMany::__construct %s - Case: ' . $check['case'];

		$model    = new Groups(static::$container);
		$relation = new BelongsToMany($model, 'Parts', $test['local'], $test['foreign'], $test['pvTable'], $test['pvLocal'], $test['pvForeign']);

		$this->assertEquals($check['local'], ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
		$this->assertEquals($check['foreign'], ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
		$this->assertEquals($check['pvTable'], ReflectionHelper::getValue($relation, 'pivotTable'), sprintf($msg, 'Failed to set the pivot table'));
		$this->assertEquals($check['pvLocal'], ReflectionHelper::getValue($relation, 'pivotLocalKey'), sprintf($msg, 'Failed to set the pivot local key'));
		$this->assertEquals($check['pvForeign'], ReflectionHelper::getValue($relation, 'pivotForeignKey'), sprintf($msg, 'Failed to set the pivot foreign key'));
	}

	/**
	 * @group           BelongsToMany
	 * @group           BelongsToManyContruct
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::__construct
	 */
	public function testConstructException()
	{
		$this->setExpectedException('FOF40\Model\DataModel\Relation\Exception\PivotTableNotFound');

		$model    = new Groups(static::$container);
		$relation = new BelongsToMany($model, 'Children');
	}

	/**
	 * @group           BelongsToMany
	 * @group           BelongsToManySetDataFromCollection
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::setDataFromCollection
	 * @dataProvider    BelongsToManyDataprovider::getTestSetDataFromCollection
	 */
	public function testSetDataFromCollection($test, $check)
	{
		$msg = 'BelongsToMany::setDataFromCollection %s - Case: ' . $check['case'];

		$parts = new Parts(static::$container);
		$model = new Groups(static::$container);
		$model->find(2);
		$relation = new BelongsToMany($model, 'Parts');

		$items[0] = clone $parts;
		$items[0]->find(1);

		$items[1] = clone $parts;
		$items[1]->find(2);

		$items[2] = clone $parts;
		$items[2]->find(3);

		$data = new Collection($items);

		$relation->setDataFromCollection($data, $test['keymap']);

		$this->assertCount($check['count'], ReflectionHelper::getValue($relation, 'data'), sprintf($msg, ''));
	}

	/**
	 * @group           BelongsToMany
	 * @group           BelongsToManyGetCountSubquery
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::getCountSubquery
	 */
	public function testGetCountSubquery()
	{
		$model    = new Groups(static::$container);
		$relation = new BelongsToMany($model, 'Parts');

		$result = $relation->getCountSubquery();

		$check = '
SELECT COUNT(*)
FROM `#__fakeapp_parts` AS `reltbl`
INNER JOIN `#__fakeapp_parts_groups` AS `pivotTable` ON(`pivotTable`.`fakeapp_part_id` = `reltbl`.`fakeapp_part_id`)
WHERE `pivotTable`.`fakeapp_group_id` =`#__fakeapp_groups`.`fakeapp_group_id`';

		$this->assertInstanceOf('\JDatabaseQuery', $result, 'BelongsToMany::getCountSubquery Should return an instance of Query');
		$this->assertEquals($check, (string) $result, 'BelongsToMany::getCountSubquery Failed to return the correct query');
	}

	/**
	 * @group           BelongsToMany
	 * @group           BelongsToManySaveAll
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::saveAll
	 */
	public function testSaveAll()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->find(1);
		$relation = new BelongsToMany($model, 'Parts');

		$items = [];

		// Let's mix datamodels with integers
		$items[0] = new Parts(static::$container);
		$items[0]->find(1);
		$items[0]->description = 'Modified';

		for ($i = 1; $i <= 55; $i++)
		{
			$items[] = $i;
		}

		$data = new Collection($items);

		ReflectionHelper::setValue($relation, 'data', $data);

		$relation->saveAll();

		$db = JoomlaFactory::getDbo();

		// First of all double check if the part was updated
		$query = $db->getQuery(true)
			->select($db->qn('description'))
			->from($db->qn('#__fakeapp_parts'))
			->where($db->qn('fakeapp_part_id') . ' = ' . $db->q(1));
		$descr = $db->setQuery($query)->loadResult();

		$this->assertEquals('Modified', $descr, 'BelongsToMany::saveAll Failed to save item in the relationship');

		// Then let's check if all the items were saved in the glue table
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q(1));
		$count = $db->setQuery($query)->loadResult();

		$this->assertEquals(55, $count, 'BelongsToMany::saveAll Failed to save data inside the glue table');
	}

	/**
	 * Test adding new related items
	 *
	 * @group           BelongsToMany
	 * @group           BelongsToManySaveAll
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::saveAll
	 */
	public function testCaseAddNewRelatedItem()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->belongsToMany('parts', 'Parts');
		$model->find(1);
		$relation = $model->getRelations()->getRelation('parts');

		$existingParts = $model->parts->toArray();

		$this->assertCount(3, $existingParts, 'Existing relations\' count must reflect items in the database');

		$newPart = new Parts(static::$container);
		$newPart->bind([
			'description' => 'Shiny new part',
		]);
		$model->parts->add($newPart);

		$relation->saveAll();

		$db             = JoomlaFactory::getDbo();
		$query          = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q(1));
		$savedRelations = $db->setQuery($query)->loadAssocList('fakeapp_part_id');

		$newPartId = $newPart->getId();

		$this->assertArrayHasKey(1, $savedRelations);
		$this->assertArrayHasKey(2, $savedRelations);
		$this->assertArrayHasKey(3, $savedRelations);
		$this->assertArrayHasKey($newPartId, $savedRelations);
	}

	/**
	 * Test adding new related items
	 *
	 * @group           BelongsToMany
	 * @group           BelongsToManySaveAll
	 * @covers          FOF40\Model\DataModel\Relation\BelongsToMany::saveAll
	 */
	public function testCaseAddExistingRelatedItem()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->belongsToMany('parts', 'Parts');
		$model->find(2);
		$relation = $model->getRelations()->getRelation('parts');

		$existingParts = $model->parts->toArray();

		$this->assertCount(2, $existingParts, 'Existing relations\' count must reflect items in the database');

		$newPart = new Parts(static::$container);
		$newPart->find(3);
		$model->parts->add($newPart);

		$relation->saveAll();

		$db             = JoomlaFactory::getDbo();
		$query          = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q(1));
		$savedRelations = $db->setQuery($query)->loadAssocList('fakeapp_part_id');

		$this->assertArrayHasKey(1, $savedRelations);
		$this->assertArrayHasKey(2, $savedRelations);
		$this->assertArrayHasKey(3, $savedRelations);
	}

	public function testCaseAddExistingRelatedItemToNewRecord()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->belongsToMany('parts', 'Parts');
		$model->description = 'Something new';

		$existingParts = $model->parts->toArray();

		$this->assertCount(0, $existingParts, 'Existing relations\' count must be zero for a new record');

		$newPart = new Parts(static::$container);
		$newPart->find(1);
		$model->parts->add($newPart);

		$model->push();

		$db             = JoomlaFactory::getDbo();
		$query          = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q($model->getId()));
		$savedRelations = $db->setQuery($query)->loadAssocList('fakeapp_part_id');

		$this->assertArrayHasKey(1, $savedRelations);
	}

	public function testCaseAddNewRelatedItemToNewRecord()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->belongsToMany('parts', 'Parts');
		$model->description = 'Something new';

		$existingParts = $model->parts->toArray();

		$this->assertCount(0, $existingParts, 'Existing relations\' count must be zero for a new record');

		$newPart              = new Parts(static::$container);
		$newPart->description = 'Yet another new part';
		$model->parts->add($newPart);

		$model->push();

		$db             = JoomlaFactory::getDbo();
		$query          = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q($model->getId()));
		$savedRelations = $db->setQuery($query)->loadAssocList('fakeapp_part_id');

		$this->assertNotNull($newPart->getId());
		$this->assertArrayHasKey($newPart->getId(), $savedRelations);
	}

	public function testCaseDeleteRelatedItemFromExistingRecord()
	{
		$platform            = static::$container->platform;
		$platform::$user     = new User(42);
		$platform::$user->id = 42;

		$model = new Groups(static::$container);
		$model->belongsToMany('parts', 'Parts');
		$model->find(2);

		$existingParts = $model->parts->toArray();

		$model->parts->removeById(2);

		$model->push();

		$db             = JoomlaFactory::getDbo();
		$query          = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__fakeapp_parts_groups'))
			->where($db->qn('fakeapp_group_id') . ' = ' . $db->q($model->getId()));
		$savedRelations = $db->setQuery($query)->loadAssocList('fakeapp_part_id');

		// This must still exist
		$this->assertArrayHasKey(1, $savedRelations);
		// This is the one we deleted
		$this->assertArrayNotHasKey(2, $savedRelations);
	}

	/**
	 * @group       BelongsToMany
	 * @group       BelongsToManyGetNew
	 * @covers      FOF40\Model\DataModel\Relation\BelongsToMany::getNew
	 */
	public function testGetNew()
	{
		$model    = new Groups(static::$container);
		$relation = new BelongsToMany($model, 'Parts');

		$this->setExpectedException('FOF40\Model\DataModel\Relation\Exception\NewNotSupported');

		$relation->getNew();
	}
}
