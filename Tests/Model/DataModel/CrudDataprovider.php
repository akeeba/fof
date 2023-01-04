<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class DataModelCrudDataprovider
{
	public static function getTestSave()
	{
		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => 1,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => ['title' => 'foobar'],
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Updating object without any "special" field',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'id'          => 1,
				'row'         => (object) ['foftest_bare_id' => 1, 'title' => 'foobar'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => ['title' => 'foobar'],
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => 1,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => null,
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Updating the record, change the data in the onBeforeSave dispatcher event',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'id'          => 1,
				'row'         => (object) ['foftest_bare_id' => 1, 'title' => 'foobar'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => ['title' => 'foobar'],
					'blankId'    => false,
				],
				'id'        => 1,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => null,
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Updating the record, change the data in the dispatcher event',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'id'          => 1,
				'row'         => (object) ['foftest_bare_id' => 1, 'title' => 'foobar'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => true,
				],
				'id'        => 1,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => null,
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Blank out the id before saving the record (dispatcher event)',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'id'          => 'max',
				'row'         => (object) ['title' => 'First Row'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => null,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => ['title' => 'foobar'],
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Inserting a new record without any "special" field',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'id'          => 'max',
				'row'         => (object) ['title' => 'foobar'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => ['title' => 'foobar'],
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => null,
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'relations' => null,
				'data'      => null,
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Inserting a new record, changing the data in the onBeforeCreate dispatcher event',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'id'          => 'max',
				'row'         => (object) ['title' => 'foobar'],
				'created_on'  => false,
				'modified_on' => false,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => 1,
				'table_id'  => 'foftest_foobar_id',
				'table'     => '#__foftest_foobars',
				'relations' => null,
				'data'      => ['title' => 'foobar'],
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Updating object with special field',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'id'          => 1,
				'row'         => (object) [
					'foftest_foobar_id' => 1,
					'title'             => 'foobar',
					// Slug is not updated since we are moking the dispatcher events
					'slug'              => 'guinea-pig-row',
					'enabled'           => 1,
					'ordering'          => 1,
					'hits'              => 0,
					'asset_id'          => 0,
					'access'            => 2,
					'locked_on'         => '0000-00-00 00:00:00',
					'locked_by'         => 0,
					'created_by'        => 42,
					'modified_by'       => 99,
					'created_on'        => '0000-00-00 00:00:00',
				],
				'created_on'  => false,
				'modified_on' => true,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => 1,
				'table_id'  => 'foftest_foobar_id',
				'table'     => '#__foftest_foobars',
				'relations' => null,
				'data'      => ['title' => 'foobar'],
				'ordering'  => 'ordering',
				'ignore'    => null,
			],
			[
				'case'        => 'Updating object with special field, passing an ordering field',
				'reorder'     => "`ordering` = '1'",
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeUpdate' => 1, 'onAfterUpdate' => 1, 'onAfterSave' => 1],
				'id'          => 1,
				'row'         => (object) [
					'foftest_foobar_id' => 1,
					'title'             => 'foobar',
					// Slug is not updated since we are moking the dispatcher events
					'slug'              => 'guinea-pig-row',
					'enabled'           => 1,
					'ordering'          => 1,
					'hits'              => 0,
					'asset_id'          => 0,
					'access'            => 2,
					'locked_on'         => '0000-00-00 00:00:00',
					'locked_by'         => 0,
					'created_by'        => 42,
					'modified_by'       => 99,
					'created_on'        => '0000-00-00 00:00:00',
				],
				'created_on'  => false,
				'modified_on' => true,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'dataSave'   => null,
					'dataCreate' => null,
					'dataUpdate' => null,
					'blankId'    => false,
				],
				'id'        => null,
				'table_id'  => 'foftest_foobar_id',
				'table'     => '#__foftest_foobars',
				'relations' => null,
				'data'      => ['title' => 'foobar'],
				'ordering'  => '',
				'ignore'    => null,
			],
			[
				'case'        => 'Inserting a new record with special field',
				'reorder'     => false,
				'modelEvents' => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'dispEvents'  => ['onBeforeSave' => 1, 'onBeforeCreate' => 1, 'onAfterCreate' => 1, 'onAfterSave' => 1],
				'id'          => 'max',
				'row'         => (object) [
					'title'       => 'foobar',
					// Slug is not updated since we are moking the dispatcher events
					'slug'        => '',
					'enabled'     => 0,
					'ordering'    => 0,
					'hits'        => 0,
					'asset_id'    => 0,
					'access'      => 0,
					'locked_on'   => '0000-00-00 00:00:00',
					'locked_by'   => 0,
					'created_by'  => 99,
					'modified_by' => 0,
					'modified_on' => '0000-00-00 00:00:00',
				],
				'created_on'  => true,
				'modified_on' => false,
			],
		];

		return $data;
	}

	public static function getTestBind()
	{
		$data[] = [
			[
				'mock'    => [
					'beforeDisp' => null,
				],
				'data'    => [
					'foftest_bare_id' => 1,
					'title'           => 'test',
				],
				'ignore'  => [],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Data array contains properties that exists',
				'dispatcher' => 2,
				'bind'       => ['foftest_bare_id' => 1, 'title' => 'test'],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'beforeDisp' => null,
				],
				'data'    => [
					'foftest_bare_id' => 1,
					'title'           => 'test',
				],
				'ignore'  => ['title'],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Data array contains properties that exists, ignoring some of them (array format)',
				'dispatcher' => 2,
				'bind'       => ['foftest_bare_id' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'beforeDisp' => null,
				],
				'data'    => [
					'foftest_foobar_id' => 1,
					'title'             => 'test',
					'enabled'           => 1,
				],
				'ignore'  => 'title enabled',
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Data array contains properties that exists, ignoring some of them (string format)',
				'dispatcher' => 2,
				'bind'       => ['foftest_foobar_id' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'beforeDisp' => null,
				],
				'data'    => [
					'foftest_bare_id' => 1,
					'title'           => 'test',
					'foobar'          => 'foo',
				],
				'ignore'  => [],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Trying to bind a property that does not exist',
				'dispatcher' => 2,
				'bind'       => ['foftest_bare_id' => 1, 'title' => 'test'],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'beforeDisp' => [
						'foftest_bare_id' => 1,
						'title'           => 'test',
					],
				],
				'data'    => null,
				'ignore'  => [],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Passing invalid data, however the onBeforeBind converts it to a valid one',
				'dispatcher' => 2,
				'bind'       => ['foftest_bare_id' => 1, 'title' => 'test'],
			],
		];

		return $data;
	}

	public static function getTestBindException()
	{
		$data[] = [
			[
				'data' => '',
			],
		];

		$data[] = [
			[
				'data' => 1,
			],
		];

		$data[] = [
			[
				'data' => null,
			],
		];

		$data[] = [
			[
				'data' => false,
			],
		];

		return $data;
	}

	public static function getTestCheck()
	{
		$data[] = [
			[
				'mock' => [
					'auto' => false,
				],
				'load' => null,
			],
			[
				'case'      => 'No autochecks set',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'auto' => true,
				],
				'load' => 1,
			],
			[
				'case'      => 'Table loaded',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'auto' => true,
				],
				'load' => null,
			],
			[
				'case'      => 'Check failed',
				'exception' => 'COM_FAKEAPP_NESTEDSET_ERR_TITLE_EMPTY',
			],
		];

		return $data;
	}

	public static function getTestDelete()
	{
		$data[] = [
			[
				'id'   => null,
				'soft' => true,
			],
			[
				'case'  => 'Id not provided, soft delete',
				'trash' => true,
				'force' => false,
			],
		];

		$data[] = [
			[
				'id'   => null,
				'soft' => false,
			],
			[
				'case'  => 'Id not provided, db delete',
				'trash' => false,
				'force' => true,
			],
		];

		$data[] = [
			[
				'id'   => 2,
				'soft' => true,
			],
			[
				'case'  => 'Id provided, soft delete',
				'trash' => true,
				'force' => false,
			],
		];

		$data[] = [
			[
				'id'   => 2,
				'soft' => false,
			],
			[
				'case'  => 'Id provided, db delete',
				'trash' => false,
				'force' => true,
			],
		];

		return $data;
	}

	public static function getTestFindOrFail()
	{
		$data[] = [
			[
				'mock' => [
					'getId' => 1,
				],
				'keys' => null,
			],
			[
				'case'      => 'Record found, not passing any keys',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getId' => null,
				],
				'keys' => null,
			],
			[
				'case'      => 'Record not found, not passing any keys',
				'exception' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getId' => 1,
				],
				'keys' => 1,
			],
			[
				'case'      => 'Record found, passing keys',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getId' => null,
				],
				'keys' => 1,
			],
			[
				'case'      => 'Record not found, passing keys',
				'exception' => true,
			],
		];

		return $data;
	}

	public static function getTestFind()
	{
		$data[] = [
			[
				'mock' => [
					'id'       => '',
					'state_id' => 0,
					'keys'     => null,
				],
				'keys' => 1,
			],
			[
				'case' => 'Passing the record id',
				'bind' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id'       => 0,
					'state_id' => 1,
					'keys'     => null,
				],
				'keys' => '',
			],
			[
				'case' => 'No argument, no object id, getting it from the state',
				'bind' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id'       => 1,
					'state_id' => 0,
					'keys'     => null,
				],
				'keys' => '',
			],
			[
				'case' => 'No argument, getting the id from the object',
				'bind' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id'       => null,
					'state_id' => 0,
					'keys'     => 1,
				],
				'keys' => '',
			],
			[
				'case' => 'No argument, getting the id from the event dispatcher',
				'bind' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id'       => null,
					'state_id' => 0,
					'keys'     => null,
				],
				'keys' => null,
			],
			[
				'case' => 'No key set anywhere',
				'bind' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id'       => null,
					'state_id' => 0,
					'keys'     => null,
				],
				'keys' => [
					'title' => 'First Row',
				],
			],
			[
				'case' => 'Passing an indexed array',
				'bind' => true,
			],
		];

		return $data;
	}

	public static function getTestForceDelete()
	{
		$data[] = [
			[
				'mock' => [
					'id' => 1,
				],
				'id'   => 1,
			],
			[
				'case' => 'Passing the id',
				'id'   => 1,
				'find' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'id' => 1,
				],
				'id'   => null,
			],
			[
				'case' => 'Loaded record',
				'id'   => 1,
				'find' => false,
			],
		];

		return $data;
	}

	public static function getTestFirstOrCreate()
	{
		$data[] = [
			[
				'mock' => [
					// I just need to return any value to flag the record as loaded
					'first' => 'foobar',
				],
			],
			[
				'case'   => 'I was able to get first record',
				'create' => false,
				'result' => 'foobar',
			],
		];

		$data[] = [
			[
				'mock' => [
					'first' => null,
				],
			],
			[
				'case'   => "I couldn't get the first record",
				'create' => true,
				'result' => 'object',
			],
		];

		return $data;
	}

	public static function getTestFirstOrFail()
	{
		$data[] = [
			[
				'mock' => [
					// I just need to return any value to flag the record as loaded
					'first' => 'foobar',
				],
			],
			[
				'case'      => 'I was able to get first record',
				'exception' => false,
				'result'    => 'foobar',
			],
		];

		$data[] = [
			[
				'mock' => [
					'first' => null,
				],
			],
			[
				'case'      => "I couldn't get the first record",
				'exception' => true,
				'result'    => '',
			],
		];

		return $data;
	}

	public static function getTestFirstOrNew()
	{
		$data[] = [
			[
				'mock' => [
					// I just need to return any value to flag the record as loaded
					'first' => 'foobar',
				],
			],
			[
				'case'   => 'I was able to get first record',
				'reset'  => false,
				'result' => 'foobar',
			],
		];

		$data[] = [
			[
				'mock' => [
					'first' => null,
				],
			],
			[
				'case'   => "I couldn't get the first record",
				'reset'  => true,
				'result' => 'object',
			],
		];

		return $data;
	}

	public static function getTestStore()
	{
		$data[] = [
			[
				'exception' => false,
			],
			[
				'result' => true,
			],
		];

		$data[] = [
			[
				'exception' => true,
			],
			[
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestLoad()
	{
		$data[] = [
			[
				'reset'     => false,
				'exception' => false,
			],
			[
				'reset'  => false,
				'result' => true,
			],
		];

		$data[] = [
			[
				'reset'     => true,
				'exception' => true,
			],
			[
				'reset'  => true,
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestCanDelete()
	{
		$data['Empty join array'] = [
			[
				'id'    => 1,
				'load'  => 0,
				'joins' => [],
			],
			[
				'exception' => false,
				'message'   => '',
			],
		];

		$data['Joins are not defined'] = [
			[
				'id'    => 1,
				'load'  => 0,
				'joins' => null,
			],
			[
				'exception' => false,
				'message'   => '',
			],
		];

		$data['Parent with 1 child - ID passed'] = [
			[
				'id'    => 1,
				'load'  => 0,
				'joins' => [
					[
						'idfield'   => 'fakeapp_child_id',
						'idalias'   => 'child_id',
						'name'      => '#__fakeapp_children',
						'joinfield' => 'fakeapp_parent_id',
						'label'     => 'CHILD',
					],
				],
			],
			[
				'exception' => '\RuntimeException',
				'message'   => '<ul><li>COM_FAKEAPP_PARENTS_NODELETE_CHILD</li></ul>',
			],
		];

		$data['Parent with 1 child - Loaded table'] = [
			[
				'id'    => 0,
				'load'  => 1,
				'joins' => [
					[
						'idfield'   => 'fakeapp_child_id',
						'idalias'   => 'child_id',
						'name'      => '#__fakeapp_children',
						'joinfield' => 'fakeapp_parent_id',
						'label'     => 'CHILD',
					],
				],
			],
			[
				'exception' => '\RuntimeException',
				'message'   => '<ul><li>COM_FAKEAPP_PARENTS_NODELETE_CHILD</li></ul>',
			],
		];

		$data['Parent with no children - delete allowed'] = [
			[
				'id'    => 3,
				'load'  => 0,
				'joins' => [
					[
						'idfield'   => 'fakeapp_child_id',
						'idalias'   => 'child_id',
						'name'      => '#__fakeapp_children',
						'joinfield' => 'fakeapp_parent_id',
						'label'     => 'CHILD',
					],
				],
			],
			[
				'exception' => false,
				'message'   => '',
			],
		];

		$data['Join array missing some fields'] = [
			[
				'id'    => 3,
				'load'  => 0,
				'joins' => [
					[
						'idfield'   => 'fakeapp_child_id',
						'idalias'   => 'child_id',
						'name'      => '#__fakeapp_children',
						'joinfield' => 'fakeapp_parent_id',
					],
				],
			],
			[
				'exception' => '\InvalidArgumentException',
				'message'   => 'Join array missing some keys, please check the documentation',
			],
		];

		$data['Table not loaded an no id is passed'] = [
			[
				'id'    => 0,
				'load'  => 0,
				'joins' => [],
			],
			[
				'exception' => '\InvalidArgumentException',
				'message'   => 'Master table should be loaded or an ID should be passed',
			],
		];

		return $data;
	}
}
