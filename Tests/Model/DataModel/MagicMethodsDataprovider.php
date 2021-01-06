<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class MagicMethodsDataprovider
{
	public static function getTest__construct()
	{
		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'foftest_bare_id',
				'table'       => '#__foftest_bares',
				'knownFields' => null,
				'autoChecks'  => true,
				'skipChecks'  => ['title'],
				'aliasFields' => ['foobar' => 'title'],
				'behaviours'  => ['foo', 'bar'],
				'fillable'    => '',
				'guarded'     => '',
				'relations'   => null,
			],
			[
				'case'         => 'Passing id, tablename, autochecks are on, skipchecks fields, alias field and behaviours',
				'addBehaviour' => 4,
				'id'           => 'foftest_bare_id',
				'table'        => '#__foftest_bares',
				'fields'       => [
					'foftest_bare_id' => (object) [
						'Field'      => 'foftest_bare_id',
						'Type'       => 'int',
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
				'autochecks'   => true,
				'skipchecks'   => ['title'],
				'alias'        => ['foobar' => 'title'],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => null,
				'table'       => null,
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => null,
				'relations'   => null,
			],
			[
				'case'         => 'Not passing anything',
				'addBehaviour' => 2,
				'id'           => 'fakeapp_test_id',
				'table'        => '#__fakeapp_tests',
				'fields'       => [
					'fakeapp_test_id' => (object) [
						'Field'      => 'fakeapp_test_id',
						'Type'       => 'int',
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
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'foftest_bare_id',
				'table'       => '#__foftest_bares',
				'knownFields' => [
					'foftest_bare_id' => (object) [
						'Field'   => 'foftest_bare_id',
						'Type'    => 'int(10) unsigned',
						'Default' => null,
					],
					'title'           => (object) [
						'Field'   => 'title',
						'Type'    => 'varchar(50)',
						'Default' => null,
					],
				],
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => '',
				'guarded'     => '',
				'relations'   => null,
			],
			[
				'case'         => 'Passing id, tablename and known fields',
				'addBehaviour' => 2,
				'id'           => 'foftest_bare_id',
				'table'        => '#__foftest_bares',
				'fields'       => [
					'foftest_bare_id' => (object) [
						'Field'   => 'foftest_bare_id',
						'Type'    => 'int(10) unsigned',
						'Default' => null,
					],
					'title'           => (object) [
						'Field'   => 'title',
						'Type'    => 'varchar(50)',
						'Default' => null,
					],
				],
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'foftest_bare_id',
				'table'       => '#__foftest_bares',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => ['foobar' => 'description'],
				'behaviours'  => null,
				'fillable'    => ['title', 'wrong', 'foobar'],
				'guarded'     => '',
				'relations'   => null,
			],
			[
				'case'         => 'Setting up fillable fields, no guarded ones',
				'addBehaviour' => 2,
				'id'           => 'foftest_bare_id',
				'table'        => '#__foftest_bares',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => ['foobar' => 'description'],
				'fillable'     => ['title', 'description'],
				'autofill'     => true,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [
						'title' => 'test',
					],
				],
				'id'          => 'foftest_bare_id',
				'table'       => '#__foftest_bares',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => ['foobar' => 'description'],
				'behaviours'  => null,
				'fillable'    => ['title', 'wrong', 'foobar'],
				'guarded'     => '',
				'relations'   => null,
			],
			[
				'case'         => 'Setting up fillable fields, no guarded ones, data in the request',
				'addBehaviour' => 2,
				'id'           => 'foftest_bare_id',
				'table'        => '#__foftest_bares',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => ['foobar' => 'description'],
				'fillable'     => ['title', 'description'],
				'autofill'     => true,
				'guarded'      => [],
				'values'       => ['title' => 'test', 'description' => null],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [
						'title'       => 'test',
						'description' => 'test',
					],
				],
				'id'          => 'foftest_bare_id',
				'table'       => '#__foftest_bares',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => ['foobar' => 'description'],
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => ['foobar'],
				'relations'   => null,
			],
			[
				'case'         => 'Setting up guarded fields, no fillable ones, data in the request',
				'addBehaviour' => 2,
				'id'           => 'foftest_bare_id',
				'table'        => '#__foftest_bares',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => ['foobar' => 'description'],
				'fillable'     => [],
				'autofill'     => true,
				'guarded'      => ['description'],
				'values'       => ['title' => 'test', 'description' => null],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [
						'title'       => 'test',
						'description' => 'test',
					],
				],
				'id'          => 'id',
				'table'       => '#__foftest_defaults',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => ['title', 'description'],
				'guarded'     => ['description'],
				'relations'   => null,
			],
			[
				'case'         => 'Setting up guarded fields AND fillable ones, data in the request',
				'addBehaviour' => 2,
				'id'           => 'id',
				'table'        => '#__foftest_defaults',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => ['title', 'description'],
				'autofill'     => true,
				'guarded'      => ['description'],
				'values'       => [
					'title'      => 'test', 'description' => null,
					'start_date' => '0000-00-00 00:00:00',
				],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [
						'title'       => 'test',
						'description' => 'test',
					],
				],
				'id'          => 'id',
				'table'       => '#__foftest_defaults',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => ['title', 'description'],
				'guarded'     => ['description'],
				'relations'   => null,
			],
			[
				'case'         => 'Setting up guarded fields AND fillable ones, data in the request, table with defaults values',
				'addBehaviour' => 2,
				'id'           => 'id',
				'table'        => '#__foftest_defaults',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => ['title', 'description'],
				'autofill'     => true,
				'guarded'      => ['description'],
				'values'       => [
					'title'      => 'test', 'description' => null,
					'start_date' => '0000-00-00 00:00:00',
				],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'fakeapp_parent_id',
				'table'       => '#__fakeapp_parents',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => null,
				'relations'   => '',
			],
			[
				'case'         => 'Passing a relation - Wrong format',
				'addBehaviour' => 2,
				'id'           => 'fakeapp_parent_id',
				'table'        => '#__fakeapp_parents',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'fakeapp_parent_id',
				'table'       => '#__fakeapp_parents',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => null,
				'relations'   => [],
			],
			[
				'case'         => 'Passing a relation - Wrong format',
				'addBehaviour' => 2,
				'id'           => 'fakeapp_parent_id',
				'table'        => '#__fakeapp_parents',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'fakeapp_parent_id',
				'table'       => '#__fakeapp_parents',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => null,
				'relations'   => ['dummy' => ''],
			],
			[
				'case'         => 'Passing a relation - Wrong format',
				'addBehaviour' => 2,
				'id'           => 'fakeapp_parent_id',
				'table'        => '#__fakeapp_parents',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => [],
				'counterApp'   => 0,
			],
		];

		$data[] = [
			[
				'mock'        => [
					'state' => [],
				],
				'id'          => 'fakeapp_parent_id',
				'table'       => '#__fakeapp_parents',
				'knownFields' => null,
				'autoChecks'  => null,
				'skipChecks'  => null,
				'aliasFields' => null,
				'behaviours'  => null,
				'fillable'    => null,
				'guarded'     => null,
				'relations'   => [
					[
						'itemName'          => 'children',
						'type'              => 'hasMany',
						'foreignModelClass' => 'Fakeapp\Model\Children',
						'localKey'          => 'fakeapp_parent_id',
						'foreignKey'        => 'fakeapp_parent_id',
					],
				],
			],
			[
				'case'         => 'Passing a relation - Correct format',
				'addBehaviour' => 2,
				'id'           => 'fakeapp_parent_id',
				'table'        => '#__fakeapp_parents',
				'fields'       => null,
				'autochecks'   => true,
				'skipchecks'   => [],
				'alias'        => [],
				'fillable'     => [],
				'autofill'     => false,
				'guarded'      => [],
				'values'       => [],
				'relations'    => ['children'],
				'counterApp'   => 0,
			],
		];

		return $data;
	}

	public static function getTest__call()
	{
		$data[] = [
			[
				'method'   => 'dummyProperty',
				'argument' => null,
				'mock'     => [
					'magic' => false,
				],
			],
			[
				'case'         => 'Property with a specific method, no argument passed',
				'method'       => 'scopeDummyProperty',
				'property'     => 'dummyProperty',
				'value'        => 'default',
				'count'        => 1,
				'magic'        => false,
				'relationCall' => false,
			],
		];

		$data[] = [
			[
				'method'   => 'dummyPropertyNoFunction',
				'argument' => null,
				'mock'     => [
					'magic' => false,
				],
			],
			[
				'case'         => 'Property without a specific method, no argument passed',
				'method'       => 'scopeDummyPropertyNoFunction',
				'property'     => 'dummyPropertyNoFunction',
				'value'        => 'default',
				'count'        => 0,
				'magic'        => true,
				'relationCall' => false,
			],
		];

		$data[] = [
			[
				'method'   => 'dummyPropertyNoFunction',
				'argument' => ['test', null],
				'mock'     => [
					'magic' => true,
				],
			],
			[
				'case'         => 'Property without a specific method, a magic method exists inside the relation manager',
				'method'       => 'scopeDummyPropertyNoFunction',
				'property'     => 'dummyPropertyNoFunction',
				'value'        => 'default',
				'count'        => 0,
				'magic'        => true,
				'relationCall' => true,
			],
		];

		return $data;
	}

	public static function getTest__isset()
	{
		$data[] = [
			[
				'mock'     => [
					'getField'    => 1,
					'magic'       => '',
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foftest_foobar_id',
			],
			[
				'case'        => 'Field is set and has a NOT NULL value',
				'getField'    => 'foftest_foobar_id',
				'magic'       => false,
				'relationGet' => false,
				'isset'       => true,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => null,
					'magic'       => '',
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foftest_foobar_id',
			],
			[
				'case'        => 'Field is set and has a NULL value',
				'getField'    => 'foftest_foobar_id',
				'magic'       => false,
				'relationGet' => false,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 1,
					'magic'       => '',
					'alias'       => [
						'foobar' => 'foftest_foobar_id',
					],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field had an alias and has a NOT NULL value',
				'getField'    => 'foftest_foobar_id',
				'magic'       => false,
				'relationGet' => false,
				'isset'       => true,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => null,
					'magic'       => '',
					'alias'       => [
						'foobar' => 'foftest_foobar_id',
					],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field had an alias and has a NULL value',
				'getField'    => 'foftest_foobar_id',
				'magic'       => false,
				'relationGet' => false,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => null,
					'magic'       => false,
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field is not set and is not a magic property',
				'getField'    => false,
				'magic'       => 'foobar',
				'relationGet' => false,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => null,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => 1,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field is not set and is a magic property, returns NOT NULL',
				'getField'    => false,
				'magic'       => 'foobar',
				'relationGet' => true,
				'isset'       => true,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => null,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field is not set and is a magic property, returns NULL',
				'getField'    => false,
				'magic'       => 'foobar',
				'relationGet' => true,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => false,
					'magic'       => '',
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'fltState',
			],
			[
				'case'        => 'Field starts with flt, no magic property set',
				'getField'    => null,
				'magic'       => 'state',
				'relationGet' => false,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => false,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'fltState',
			],
			[
				'case'        => 'Field starts with flt, magic property set and returns NULL',
				'getField'    => null,
				'magic'       => 'state',
				'relationGet' => true,
				'isset'       => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => false,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => 1,
				],
				'property' => 'fltState',
			],
			[
				'case'        => 'Field starts with flt, magic property set and returns NOT NULL',
				'getField'    => null,
				'magic'       => 'state',
				'relationGet' => true,
				'isset'       => true,
			],
		];

		return $data;
	}

	public static function getTest__get()
	{
		$data[] = [
			[
				'mock'     => [
					'getField'    => 1,
					'getState'    => 0,
					'magic'       => '',
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foftest_foobar_id',
			],
			[
				'case'        => 'Standard field of the DataModel',
				'getField'    => 'foftest_foobar_id',
				'getState'    => false,
				'magic'       => false,
				'relationGet' => false,
				'get'         => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 1,
					'getState'    => 0,
					'magic'       => '',
					'alias'       => [
						'foobar' => 'foftest_foobar_id',
					],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Standard field with an alias of the DataModel',
				'getField'    => 'foftest_foobar_id',
				'getState'    => false,
				'magic'       => false,
				'relationGet' => false,
				'get'         => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 0,
					'getState'    => 1,
					'magic'       => false,
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field with has not a magic property method inside the relation manager',
				'getField'    => false,
				'getState'    => 'foobar',
				'magic'       => 'foobar',
				'relationGet' => false,
				'get'         => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 0,
					'getState'    => 0,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => 1,
				],
				'property' => 'foobar',
			],
			[
				'case'        => 'Field has a magic property method inside the relation manager',
				'getField'    => false,
				'getState'    => false,
				'magic'       => 'foobar',
				'relationGet' => 'foobar',
				'get'         => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 0,
					'getState'    => 1,
					'magic'       => false,
					'alias'       => [],
					'relationGet' => null,
				],
				'property' => 'fltFoobar',
			],
			[
				'case'        => 'Field with has not a magic property method inside the relation manager - Magic name',
				'getField'    => false,
				'getState'    => 'foobar',
				'magic'       => 'foobar',
				'relationGet' => false,
				'get'         => 1,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'getField'    => 0,
					'getState'    => 0,
					'magic'       => true,
					'alias'       => [],
					'relationGet' => 1,
				],
				'property' => 'fltFoobar',
			],
			[
				'case'        => 'Field has a magic property method inside the relation manager - Magic name',
				'getField'    => false,
				'getState'    => false,
				'magic'       => 'foobar',
				'relationGet' => 'foobar',
				'get'         => 1,
			],
		];

		return $data;
	}

	public static function getTest__set()
	{
		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'property' => 'foftest_foobar_id',
				'value'    => 10,
			],
			[
				'case'     => 'Setting a property that exists in the table',
				'call'     => false,
				'count'    => 0,
				'method'   => '',
				'setField' => 'foftest_foobar_id',
				'setState' => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [
						'foobar' => 'foftest_foobar_id',
					],
				],
				'property' => 'foobar',
				'value'    => 10,
			],
			[
				'case'     => 'Setting a property that exists in the table using an alias',
				'call'     => false,
				'count'    => 0,
				'method'   => '',
				'setField' => 'foftest_foobar_id',
				'setState' => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'property' => 'foobar',
				'value'    => 10,
			],
			[
				'case'     => 'Property does not exists, so we set the state',
				'call'     => false,
				'count'    => 0,
				'method'   => 'scopeFoobar',
				'setField' => false,
				'setState' => 'foobar',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'property' => 'dummyNoProperty',
				'value'    => 10,
			],
			[
				'case'     => 'Property does not exists, but we have a magic method scope',
				'call'     => false,
				'count'    => 1,
				'method'   => 'scopeDummyNoProperty',
				'setField' => false,
				'setState' => false,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'property' => 'fltFoobar',
				'value'    => 10,
			],
			[
				'case'     => 'Property does not exists, but its name is magic for the state',
				'call'     => false,
				'count'    => 0,
				'method'   => 'scopeFoobar',
				'setField' => false,
				'setState' => 'foobar',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'alias' => [],
				],
				'property' => 'scopeFoobar',
				'value'    => 10,
			],
			[
				'case'     => 'Property does not exists, but its name is magic for the state - Going to invoke the call method of the model',
				'call'     => true,
				'count'    => 0,
				'method'   => 'scopeFoobar',
				'setField' => false,
				'setState' => false,
			],
		];

		return $data;
	}
}
