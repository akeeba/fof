<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class DataModelGenericDataprovider
{
	public static function getTestGetTableFields()
	{
		$data[] = [
			[
				'mock'  => [
					'tables'    => null,
					'tableName' => null,
				],
				'table' => '#__foftest_bares',
			],
			[
				'case'   => 'Table exists, abstract name, loaded cache',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => null,
					'tableName' => '#__foftest_bares',
				],
				'table' => null,
			],
			[
				'case'   => 'Table exists, abstract name, loaded cache, table name got from the object',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => null,
					'tableName' => null,
				],
				'table' => '#__wrong',
			],
			[
				'case'   => 'Table does not exist, abstract name, loaded cache',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => null,
					'tableName' => null,
				],
				'table' => 'jos_foftest_bares',
			],
			[
				'case'   => 'Table exists, actual name, loaded cache',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => 'nuke',
					'tableName' => null,
				],
				'table' => '#__foftest_bares',
			],
			[
				'case'   => 'Table exists, abstract name, clean cache',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => [
						'#__foftest_bares' => 'unset',
					],
					'tableName' => null,
				],
				'table' => '#__foftest_bares',
			],
			[
				'case'   => 'Table exists, abstract name, table not inside the cache',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'tables'    => [
						'#__foftest_bares' => false,
					],
					'tableName' => null,
				],
				'table' => '#__foftest_bares',
			],
			[
				'case'   => 'Table exists, abstract name, table had a false value inside the cache',
				'result' => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int(11)',
						'Collation'  => null,
						'Null'       => 'NO',
						'Key'        => 'PRI',
						'Default'    => null,
						'Extra'      => 'auto_increment',
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
					'title'           => (object) [
						'Field'      => 'title',
						'Type'       => 'varchar(100)',
						'Collation'  => 'utf8mb4_unicode_ci',
						'Null'       => 'NO',
						'Key'        => null,
						'Default'    => null,
						'Extra'      => null,
						'Privileges' => 'select,insert,update,references',
						'Comment'    => null,
					],
				],
			],
		];

		return $data;
	}

	public static function getTestGetDbo()
	{
		$data[] = [
			[
				'nuke' => false,
			],
			[
				'case'      => 'The internal db pointer is an object',
				'dbCounter' => 0,
			],
		];

		$data[] = [
			[
				'nuke' => true,
			],
			[
				'case'      => 'The internal db pointer is not an object, getting from the container',
				'dbCounter' => 1,
			],
		];

		return $data;
	}

	public static function getTestSetFieldValue()
	{
		$data[] = [
			[
				'mock'  => [
					'alias' => [],
				],
				'name'  => 'title',
				'value' => 'bar',
			],
			[
				'case'  => 'Setting a method, no alias',
				'key'   => 'title',
				'value' => 'bar',
			],
		];

		$data[] = [
			[
				'mock'  => [
					'alias' => [
						'foo' => 'title',
					],
				],
				'name'  => 'foo',
				'value' => 'bar',
			],
			[
				'case'  => 'Setting a method, with alias',
				'key'   => 'title',
				'value' => 'bar',
			],
		];

		return $data;
	}

	public static function getTestReset()
	{
		$data[] = [
			[
				'mock'      => [
					'recordData'      => ['foftest_bare_id' => null, 'title' => null],
					'eagerRelations'  => [],
					'relationFilters' => [],
				],
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'default'   => true,
				'relations' => false,
			],
			[
				'case'           => 'Table with no defaults, no relations nor filters. Resetting to default, not resetting the relations',
				'resetRelations' => false,
				'eager'          => [],
				'data'           => [
					'foftest_bare_id' => null,
					'title'           => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => ['foftest_bare_id' => null, 'title' => null],
					'eagerRelations'  => [],
					'relationFilters' => [],
				],
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'default'   => true,
				'relations' => false,
			],
			[
				'case'           => 'Table with no defaults, no relations nor filters. Resetting to default, not resetting the relations. Additional fields set',
				'resetRelations' => false,
				'eager'          => [],
				'data'           => [
					'foftest_bare_id' => null,
					'title'           => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => ['foftest_bare_id' => null, 'title' => null],
					'eagerRelations'  => [],
					'relationFilters' => [],
				],
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'default'   => false,
				'relations' => false,
			],
			[
				'case'           => 'Table with no defaults, no relations nor filters. Not resetting to default, not resetting the relations',
				'resetRelations' => false,
				'eager'          => [],
				'data'           => [
					'foftest_bare_id' => null,
					'title'           => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => [
						'id'          => null, 'title' => null, 'start_date' => null,
						'description' => null,
					],
					'eagerRelations'  => [],
					'relationFilters' => [],
				],
				'table_id'  => 'id',
				'table'     => '#__foftest_defaults',
				'default'   => true,
				'relations' => false,
			],
			[
				'case'           => 'Table with defaults, no relations nor filters. Resetting to defaults, not resetting the relations',
				'resetRelations' => false,
				'eager'          => [],
				'data'           => [
					'id'          => null,
					'title'       => 'dummy',
					'start_date'  => '0000-00-00 00:00:00',
					'description' => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => [
						'id'          => null, 'title' => null, 'start_date' => null,
						'description' => null,
					],
					'eagerRelations'  => [],
					'relationFilters' => [],
				],
				'table_id'  => 'id',
				'table'     => '#__foftest_defaults',
				'default'   => false,
				'relations' => false,
			],
			[
				'case'           => 'Table with defaults, no relations nor filters. Not resetting to defaults, not resetting the relations',
				'resetRelations' => false,
				'eager'          => [],
				'data'           => [
					'id'          => null,
					'title'       => null,
					'start_date'  => null,
					'description' => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => [],
					'eagerRelations'  => ['foo' => 'bar'],
					'relationFilters' => ['dummy'],
				],
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'default'   => true,
				'relations' => false,
			],
			[
				'case'           => 'Relations set, but we are not resetting them',
				'resetRelations' => false,
				'eager'          => ['foo' => 'bar'],
				'data'           => [
					'foftest_bare_id' => null,
					'title'           => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'recordData'      => [],
					'eagerRelations'  => ['foo' => 'bar'],
					'relationFilters' => ['dummy'],
				],
				'table_id'  => 'foftest_bare_id',
				'table'     => '#__foftest_bares',
				'default'   => true,
				'relations' => true,
			],
			[
				'case'           => 'Relations set, we are resetting them',
				'resetRelations' => true,
				'eager'          => [],
				'data'           => [
					'foftest_bare_id' => null,
					'title'           => null,
				],
			],
		];

		return $data;
	}

	public static function getTestGetFieldValue()
	{
		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'find'     => 1,
				'property' => 'foftest_foobar_id',
				'default'  => null,
			],
			[
				'case'   => 'Getting a property that exists',
				'result' => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'find'     => null,
				'property' => 'foftest_foobar_id',
				'default'  => null,
			],
			[
				'case'   => 'Getting a property that exists, record not loaded',
				'result' => null,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'find'     => null,
				'property' => 'foobar',
				'default'  => 'test',
			],
			[
				'case'   => 'Getting a property that does not exist',
				'result' => 'test',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [
						'foobar' => 'title',
					],
				],
				'find'     => 1,
				'property' => 'foobar',
				'default'  => null,
			],
			[
				'case'   => 'Getting a property that exists using an alias',
				'result' => 'Guinea Pig row',
			],
		];

		return $data;
	}

	public static function getTestHasField()
	{
		$data[] = [
			[
				'mock'  => [
					'getAlias' => 'foftest_foobar_id',
					'fields'   => [
						'foftest_foobar_id' => 'dummy',
					],
				],
				'field' => 'foftest_foobar_id',
			],
			[
				'case'   => 'Field exists, no alias',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock'  => [
					'getAlias' => 'nothere',
					'fields'   => [
						'foftest_foobar_id' => 'dummy',
					],
				],
				'field' => 'nothere',
			],
			[
				'case'   => 'Field does not exists, no alias',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock'  => [
					'getAlias' => 'foobar',
					'fields'   => [
						'foftest_foobar_id' => 'dummy',
					],
				],
				'field' => 'foftest_foobar_id',
			],
			[
				'case'   => 'Field does no exists, has an alias',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock'  => [
					'getAlias' => 'foobar',
					'fields'   => [
						'foobar' => 'dummy',
					],
				],
				'field' => 'foftest_foobar_id',
			],
			[
				'case'   => 'Field exists, has an alias',
				'result' => true,
			],
		];

		return $data;
	}

	public static function getTestGetFieldAlias()
	{
		$data[] = [
			[
				'mock'  => [
					'alias' => [
						'foobar' => 'test',
					],
				],
				'field' => 'id',
			],
			[
				'case'   => 'Alias not set for the field',
				'result' => 'id',
			],
		];

		$data[] = [
			[
				'mock'  => [
					'alias' => [
						'id' => 'foobar',
					],
				],
				'field' => 'id',
			],
			[
				'case'   => 'Alias set for the field',
				'result' => 'foobar',
			],
		];

		return $data;
	}

	public static function getTestChunk()
	{
		$data[] = [
			[
				'mock'      => [
					'count' => 0,
				],
				'chunksize' => 5,
			],
			[
				'case' => 'Records not found',
				'get'  => 0,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'count' => 10,
				],
				'chunksize' => 5,
			],
			[
				'case' => 'Records found they are a multiple of the chunksize',
				'get'  => 2,
			],
		];

		$data[] = [
			[
				'mock'      => [
					'count' => 10,
				],
				'chunksize' => 4,
			],
			[
				'case' => 'Records found they are not a multiple of the chunksize',
				'get'  => 3,
			],
		];

		return $data;
	}

	public static function getTestBuildQuery()
	{
		$data[] = [
			[
				'mock'     => [
					'where' => [],
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query, no order field or direction',
				'filter' => true,
				'where'  => [],
				'order'  => ['`foftest_bare_id` ASC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [],
					'order' => 'title',
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query or direction, with (known) order field',
				'filter' => true,
				'where'  => [],
				'order'  => ['`title` ASC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [],
					'order' => 'foobar',
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query or direction, with (unknown) order field',
				'filter' => true,
				'where'  => [],
				'order'  => ['`foftest_bare_id` ASC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [],
					'order' => 'title',
					'dir'   => 'asc',
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query, with (known) order field and lowercase direction',
				'filter' => true,
				'where'  => [],
				'order'  => ['`title` ASC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [],
					'order' => 'title',
					'dir'   => 'DESC',
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query, with (known) order field and uppercase direction',
				'filter' => true,
				'where'  => [],
				'order'  => ['`title` DESC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [],
					'order' => 'title',
					'dir'   => 'wrong',
				],
				'override' => false,
			],
			[
				'case'   => 'No limits override, no additional query, with (known) order field and invalid direction',
				'filter' => true,
				'where'  => [],
				'order'  => ['`title` ASC'],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'where' => [
						'foobar = 1',
					],
					'order' => 'title',
					'dir'   => 'DESC',
				],
				'override' => true,
			],
			[
				'case'   => 'Limits override, additional query, with (known) order field and uppercase direction',
				'filter' => true,
				'where'  => ['foobar = 1'],
				'order'  => ['`title` DESC'],
			],
		];


		return $data;
	}

	public static function getTestGet()
	{
		$data[] = [
			[
				'mock'       => [
					'limitstart' => 10,
					'limit'      => 10,
				],
				'override'   => false,
				'limitstart' => 0,
				'limit'      => 0,
			],
			[
				'case'       => 'Not overriding the limits',
				'limitstart' => 10,
				'limit'      => 10,
			],
		];

		$data[] = [
			[
				'mock'       => [
					'limitstart' => 10,
					'limit'      => 10,
				],
				'override'   => true,
				'limitstart' => 5,
				'limit'      => 5,
			],
			[
				'case'       => 'Overriding the limits',
				'limitstart' => 5,
				'limit'      => 5,
			],
		];

		return $data;
	}

	public static function getTestAddBehaviour()
	{
		$data[] = [
			[
				'class' => 'Foofilters',
			],
			[
				'case'     => 'Adding behaviour from Fakeapp\Site\Model\Behaviour',
				'class'    => 'Fakeapp\Site\Model\Behaviour\Foofilters',
				'attached' => true,
			],
		];

		$data[] = [
			[
				'class' => 'NamedBehaviour',
			],
			[
				'case'     => 'Adding behaviour from Fakeapp\\Model\\Nestedset\\Behaviour\\NamedBehaviour',
				'class'    => 'Fakeapp\Site\Model\Behaviour\Nestedset\NamedBehaviour',
				'attached' => true,
			],
		];

		$data[] = [
			[
				'class' => 'Filters',
			],
			[
				'case'     => 'Adding behaviour from FOF40\\Model\\DataModel\\Behaviour',
				'class'    => 'FOF40\\Model\\DataModel\\Behaviour\\Filters',
				'attached' => true,
			],
		];

		$data[] = [
			[
				'class' => 'Wrong',
			],
			[
				'case'     => 'Trying to add non-existant behaviour',
				'class'    => 'FOF40\\Model\\DataModel\\Behaviour\\Wrong',
				'attached' => false,
			],
		];

		return $data;
	}

	public static function getTestOrderBy()
	{
		$data[] = [
			[
				'field' => 'foobar',
				'dir'   => 'asc',
			],
			[
				'case'  => 'Passing field and direction (lowercase)',
				'field' => 'foobar',
				'dir'   => 'ASC',
			],
		];

		$data[] = [
			[
				'field' => 'foobar',
				'dir'   => 'desc',
			],
			[
				'case'  => 'Passing field and direction (lowercase)',
				'field' => 'foobar',
				'dir'   => 'DESC',
			],
		];

		$data[] = [
			[
				'field' => 'foobar',
				'dir'   => '',
			],
			[
				'case'  => 'Passing field only',
				'field' => 'foobar',
				'dir'   => 'ASC',
			],
		];

		return $data;
	}

	public static function getTestSkip()
	{
		$data[] = [
			[
				'limitstart' => 10,
			],
			[
				'case'       => 'Limitstart is positive',
				'limitstart' => 10,
			],
		];

		$data[] = [
			[
				'limitstart' => null,
			],
			[
				'case'       => 'Limitstart is null',
				'limitstart' => 0,
			],
		];

		$data[] = [
			[
				'limitstart' => -1,
			],
			[
				'case'       => 'Limitstart is negative',
				'limitstart' => 0,
			],
		];

		$data[] = [
			[
				'limitstart' => [1],
			],
			[
				'case'       => 'Wrong type',
				'limitstart' => 0,
			],
		];

		$data[] = [
			[
				'limitstart' => new stdClass(),
			],
			[
				'case'       => 'Wrong type',
				'limitstart' => 0,
			],
		];

		$data[] = [
			[
				'limitstart' => true,
			],
			[
				'case'       => 'Wrong type',
				'limitstart' => 0,
			],
		];

		return $data;
	}

	public static function getTestTake()
	{
		$data[] = [
			[
				'limit' => 10,
			],
			[
				'case'  => 'Limit is positive',
				'limit' => 10,
			],
		];

		$data[] = [
			[
				'limit' => null,
			],
			[
				'case'  => 'Limit is null',
				'limit' => 0,
			],
		];

		$data[] = [
			[
				'limit' => -1,
			],
			[
				'case'  => 'Limit is negative',
				'limit' => 0,
			],
		];

		$data[] = [
			[
				'limit' => [1],
			],
			[
				'case'  => 'Wrong type',
				'limit' => 0,
			],
		];

		$data[] = [
			[
				'limit' => new stdClass(),
			],
			[
				'case'  => 'Wrong type',
				'limit' => 0,
			],
		];

		$data[] = [
			[
				'limit' => true,
			],
			[
				'case'  => 'Wrong type',
				'limit' => 0,
			],
		];

		return $data;
	}

	public static function getTestToJson()
	{
		$data[] = [
			[
				'pretty' => false,
			],
		];

		$data[] = [
			[
				'pretty' => true,
			],
		];

		return $data;
	}

	public static function getTestWhere()
	{
		$data[] = [
			[
				'mock'   => [
					'id_field' => 'foobar',
					'hasClass' => false,
				],
				'field'  => 'foobar',
				'method' => '=',
				'values' => null,
			],
			[
				'case'    => 'Behaviors not loaded, field is the primary key',
				'add'     => true,
				'field'   => 'foobar',
				'options' => [
					'method'   => 'search',
					'value'    => null,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'dummy',
					'hasClass' => true,
				],
				'field'  => 'foobar',
				'method' => '=',
				'values' => null,
			],
			[
				'case'    => 'Behaviors loaded, field is not the primary key',
				'add'     => false,
				'field'   => 'foobar',
				'options' => [
					'method'   => 'search',
					'value'    => null,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '<>',
				'values' => 12,
			],
			[
				'case'    => '<> method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'lt',
				'values' => 12,
			],
			[
				'case'    => 'lt method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '<',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'le',
				'values' => 12,
			],
			[
				'case'    => 'le method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '<=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'gt',
				'values' => 12,
			],
			[
				'case'    => 'gt method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '>',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'ge',
				'values' => 12,
			],
			[
				'case'    => 'ge method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '>=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'eq',
				'values' => 12,
			],
			[
				'case'    => 'eq method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'neq',
				'values' => 12,
			],
			[
				'case'    => 'neq method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'ne',
				'values' => 12,
			],
			[
				'case'    => 'ne method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '<',
				'values' => 12,
			],
			[
				'case'    => '< method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '<',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '!<',
				'values' => 12,
			],
			[
				'case'    => '!< method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!<',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '<=',
				'values' => 12,
			],
			[
				'case'    => '<= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '<=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '!<=',
				'values' => 12,
			],
			[
				'case'    => '!<= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!<=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '>',
				'values' => 12,
			],
			[
				'case'    => '> method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '>',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '!>',
				'values' => 12,
			],
			[
				'case'    => '!> method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!>',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '>=',
				'values' => 12,
			],
			[
				'case'    => '>= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '>=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '!>=',
				'values' => 12,
			],
			[
				'case'    => '!>= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!>=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '!=',
				'values' => 12,
			],
			[
				'case'    => '!= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '=',
				'values' => 12,
			],
			[
				'case'    => '= method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'like',
				'values' => 'foobar',
			],
			[
				'case'    => 'like method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'partial',
					'value'  => 'foobar',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '~',
				'values' => 'foobar',
			],
			[
				'case'    => '~ method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'partial',
					'value'  => 'foobar',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '%',
				'values' => 'foobar',
			],
			[
				'case'    => '%% method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'partial',
					'value'  => 'foobar',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '==',
				'values' => 12,
			],
			[
				'case'    => '== method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'exact',
					'value'  => 12,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '=[]',
				'values' => 12,
			],
			[
				'case'    => '=[] method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'exact',
					'value'  => 12,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '=()',
				'values' => 12,
			],
			[
				'case'    => '=() method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'exact',
					'value'  => 12,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'in',
				'values' => 12,
			],
			[
				'case'    => 'in method, values passed',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'exact',
					'value'  => 12,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => 12,
			],
			[
				'case'    => 'between method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '[]',
				'values' => 12,
			],
			[
				'case'    => '[] method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '[)',
				'values' => 12,
			],
			[
				'case'    => '[) method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '(]',
				'values' => 12,
			],
			[
				'case'    => '(] method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => [12],
			],
			[
				'case'    => 'between method, values is an array with a single element',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => [12, 22],
			],
			[
				'case'    => 'between method, values is an array, but no from/to keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'between',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => [12, 22, 'from' => 5],
			],
			[
				'case'    => 'between method, values is an array, but no "from" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'between',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => [12, 22, 'to' => 5],
			],
			[
				'case'    => 'between method, values is an array, but no "to" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'between',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '()',
				'values' => [12, 22, 'from' => 5, 'to' => 7],
			],
			[
				'case'    => 'between method, values is an array, with "from/to" keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'between',
					'from'   => 5,
					'to'     => 7,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => 12,
			],
			[
				'case'    => 'outside method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')[',
				'values' => 12,
			],
			[
				'case'    => ')[ method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '](',
				'values' => 12,
			],
			[
				'case'    => ']( method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '][',
				'values' => 12,
			],
			[
				'case'    => '][ method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => [12],
			],
			[
				'case'    => 'outside method, values is an array with a single element',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '!=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => [12, 22],
			],
			[
				'case'    => 'outside method, values is an array, but no from/to keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'outside',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => [12, 22, 'from' => 5],
			],
			[
				'case'    => 'outside method, values is an array, but no "from" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'outside',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => [12, 22, 'to' => 5],
			],
			[
				'case'    => 'outside method, values is an array, but no "to" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'outside',
					'from'   => 12,
					'to'     => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => ')(',
				'values' => [12, 22, 'from' => 5, 'to' => 7],
			],
			[
				'case'    => 'outside method, values is an array, with "from/to" keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'outside',
					'from'   => 5,
					'to'     => 7,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => 'every',
				'values' => 12,
			],
			[
				'case'    => 'every (interval) method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => 12,
			],
			[
				'case'    => 'interval method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => [12],
			],
			[
				'case'    => 'interval method, values is an array with a single item',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'value'    => 12,
					'operator' => '=',
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => [12, 22],
			],
			[
				'case'    => 'interval method, values is an array, but no value/interval keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'interval',
					'value'    => 12,
					'interval' => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => [12, 22, 'value' => 5],
			],
			[
				'case'    => 'interval method, values is an array, but no "value" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'interval',
					'value'    => 12,
					'interval' => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => [12, 22, 'interval' => 5],
			],
			[
				'case'    => 'interval method, values is an array, but no "interval" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'interval',
					'value'    => 12,
					'interval' => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '*=',
				'values' => [12, 22, 'value' => 5, 'interval' => 7],
			],
			[
				'case'    => 'interval method, values is an array, with "value/interval" keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'interval',
					'value'    => 5,
					'interval' => 7,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => 12,
			],
			[
				'case'    => '?= method, values is not an array',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'search',
					'value'  => 12,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => [12],
			],
			[
				'case'    => '?= method, values is an array with a single item',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method' => 'search',
					'value'  => [12],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => [12, 22],
			],
			[
				'case'    => '?= method, values is an array with no "operator/value" keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'operator' => 12,
					'value'    => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => [12, 22, 'operator' => 'XX'],
			],
			[
				'case'    => '?= method, values is an array with no "value" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'operator' => 12,
					'value'    => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => [12, 22, 'value' => 'XX'],
			],
			[
				'case'    => '?= method, values is an array with no "operator" key',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'operator' => 12,
					'value'    => 22,
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'id_field' => 'id',
					'hasClass' => true,
				],
				'field'  => 'id',
				'method' => '?=',
				'values' => [12, 22, 'value' => 5, 'operator' => 'XX'],
			],
			[
				'case'    => '?= method, values is an array with "operator/value" keys',
				'add'     => false,
				'field'   => 'id',
				'options' => [
					'method'   => 'search',
					'operator' => 'XX',
					'value'    => 5,
				],
			],
		];

		return $data;
	}

	public static function getTestWith()
	{
		$data[] = [
			[
				'mock'      => [
					'relNames' => ['foobar'],
				],
				'relations' => [
					'foobar' => function () {
					},
				],
			],
			[
				'case'  => 'Relation known, callback applied',
				'eager' => [
					'foobar' => function () {
					},
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'relNames' => ['foobar'],
				],
				'relations' => ['foobar'],
			],
			[
				'case'  => 'Relation known, no callback',
				'eager' => [
					'foobar' => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'relNames' => ['foobar'],
				],
				'relations' => ['dummy'],
			],
			[
				'case'  => 'Relation not known',
				'eager' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'relNames' => ['foobar'],
				],
				'relations' => [],
			],
			[
				'case'  => 'Reset the eager relations array',
				'eager' => [],
			],
		];

		return $data;
	}

	public static function getTestapplyAccessFiltering()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'  => 'Table with no access support',
				'state' => false,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'  => 'Table with access support',
				'state' => true,
			],
		];

		return $data;
	}

	public static function getTestGetContentType()
	{
		$data[] = [
			[
				'contentType' => 'com_fakeapp.foobar',
			],
			[
				'case'      => 'Content Type is set',
				'exception' => false,
				'result'    => 'com_fakeapp.foobar',
			],
		];

		$data[] = [
			[
				'contentType' => null,
			],
			[
				'case'      => 'Content Type is not set',
				'exception' => true,
				'result'    => null,
			],
		];

		return $data;
	}

	public static function getTestAddKnownField()
	{
		$data[] = [
			[
				'name'    => 'foftest_bare_id',
				'replace' => false,
			],
			[
				'case'  => 'Try to add a field that already exists - no replace',
				'field' => 'foftest_bare_id',
				'value' => null,
				'info'  => (object) [
					'Field'      => 'foftest_bare_id',
					'Type'       => 'int(11)',
					'Collation'  => null,
					'Null'       => 'NO',
					'Key'        => 'PRI',
					'Default'    => null,
					'Extra'      => 'auto_increment',
					'Privileges' => 'select,insert,update,references',
					'Comment'    => null,
				],
			],
		];

		$data[] = [
			[
				'name'    => 'foftest_bare_id',
				'replace' => true,
			],
			[
				'case'  => 'Try to add a field that already exists - replace',
				'field' => 'foftest_bare_id',
				'value' => 'foobar',
				'info'  => (object) [
					'Type'    => 'varchar(100)',
					'Default' => 'foobar',
					'Null'    => 'YES',
				],
			],
		];

		$data[] = [
			[
				'name'    => 'new_one',
				'replace' => false,
			],
			[
				'case'  => 'Adding a field that does not exist',
				'field' => 'new_one',
				'value' => 'foobar',
				'info'  => (object) [
					'Type'    => 'varchar(100)',
					'Default' => 'foobar',
					'Null'    => 'YES',
				],
			],
		];

		return $data;
	}

	public static function getTestGetAssetName()
	{
		$data[] = [
			[
				'load'     => 0,
				'assetkey' => '',
			],
			[
				'case'      => 'Asset key not defined',
				'exception' => true,
				'result'    => '',
			],
		];

		$data[] = [
			[
				'load'     => 0,
				'assetkey' => 'com_fakeapp.foobar',
			],
			[
				'case'      => 'Table not loaded',
				'exception' => true,
				'result'    => '',
			],
		];

		$data[] = [
			[
				'load'     => 1,
				'assetkey' => 'com_fakeapp.foobar',
			],
			[
				'case'      => 'Asset key present and table loaded',
				'exception' => false,
				'result'    => 'com_fakeapp.foobar.1',
			],
		];

		return $data;
	}

	public static function getTestGetForm()
	{
		$data[] = [
			[
				'data'     => [],
				'loadData' => true,
				'source'   => null,
				'mock'     => [
					'formName' => '',
					'loadForm' => true,
				],
			],
			[
				'case'    => 'No data, loading the form, no source, loadForm returns a valid form',
				'data'    => [],
				'name'    => 'com_fakeapp.nestedset.form.nestedset',
				'source'  => 'form.nestedset',
				'options' => ['control' => false, 'load_data' => true],
				'result'  => true,
				'before'  => 1,
				'after'   => 1,
			],
		];

		$data[] = [
			[
				'data'     => [],
				'loadData' => true,
				'source'   => null,
				'mock'     => [
					'formName' => 'fromobject',
					'loadForm' => true,
				],
			],
			[
				'case'    => 'No data, loading the form, no source (taken from Model name), loadForm returns a valid form',
				'data'    => [],
				'name'    => 'com_fakeapp.nestedset.fromobject',
				'source'  => 'fromobject',
				'options' => ['control' => false, 'load_data' => true],
				'result'  => true,
				'before'  => 1,
				'after'   => 1,
			],
		];

		$data[] = [
			[
				'data'     => ['foobar' => '123'],
				'loadData' => false,
				'source'   => 'default',
				'mock'     => [
					'formName' => '',
					'loadForm' => false,
				],
			],
			[
				'case'    => 'With data, not loading the form, no source, loadForm returns false',
				'data'    => ['foobar' => '123'],
				'name'    => 'com_fakeapp.nestedset.default',
				'source'  => 'default',
				'options' => ['control' => false, 'load_data' => false],
				'result'  => false,
				'before'  => 1,
				'after'   => 0,
			],
		];

		return $data;
	}

	public function getTestValidateForm()
	{
		$data[] = [
			[
				'mock' => [
					'filter'   => [123],
					'validate' => true,
					'errors'   => '',
				],
			],
			[
				'case'      => 'Validation runs fine',
				'exception' => false,
				'message'   => '',
				'result'    => [123],
			],
		];

		$data[] = [
			[
				'mock' => [
					'filter'   => [123],
					'validate' => new \Exception(),
					'errors'   => '',
				],
			],
			[
				'case'      => 'Validate returns an exception',
				'exception' => '\Exception',
				'message'   => '',
				'result'    => '',
			],
		];

		$data[] = [
			[
				'mock' => [
					'filter'   => [123],
					'validate' => false,
					'errors'   => [new \Exception()],
				],
			],
			[
				'case'      => 'Validate returns false, the error stack contains an exception',
				'exception' => '\Exception',
				'message'   => '',
				'result'    => '',
			],
		];

		$data[] = [
			[
				'mock' => [
					'filter'   => [123],
					'validate' => false,
					'errors'   => ['Error'],
				],
			],
			[
				'case'      => 'Validate returns false, the error stack contains a message',
				'exception' => 'FOF40\Model\DataModel\Exception\BaseException',
				'message'   => 'Error',
				'result'    => '',
			],
		];

		return $data;
	}

	public static function getTestGetBehaviorParam()
	{
		$data[] = [
			[
				'name'    => 'foo',
				'default' => 'test',
				'mock'    => [
					'behaviors' => [
						'foo' => 'bar',
					],
				],
			],
			[
				'case'   => 'Behavior is set',
				'result' => 'bar',
			],
		];

		$data[] = [
			[
				'name'    => 'foo',
				'default' => 'test',
				'mock'    => [
					'behaviors' => [],
				],
			],
			[
				'case'   => 'Behavior is not set',
				'result' => 'test',
			],
		];

		return $data;
	}

	public static function getTestBlacklistFilters()
	{
		$data[] = [
			[
				'list'  => null,
				'reset' => false,
			],
			[
				'case'    => 'Retrieving the whole list of filters',
				'result'  => ['test'],
				'filters' => ['test'],
			],
		];

		$data[] = [
			[
				'list'  => 'foobar',
				'reset' => false,
			],
			[
				'case'    => 'Setting a new list - no replace',
				'result'  => null,
				'filters' => ['test', 'foobar'],
			],
		];

		$data[] = [
			[
				'list'  => 'foobar',
				'reset' => true,
			],
			[
				'case'    => 'Setting a new list - with replace',
				'result'  => null,
				'filters' => ['foobar'],
			],
		];

		return $data;
	}
}
