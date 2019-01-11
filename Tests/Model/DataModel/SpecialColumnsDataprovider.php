<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class SpecialColumnsDataprovider
{
	public static function getTestReorder()
	{
		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
				],
				'where' => '',
			],
			[
				'case'  => 'Records have the same ordering as their ID',
				'order' => [1, 2, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 4, 2 => 3, 3 => 2, 4 => 1, 5 => 5],
				],
				'where' => '',
			],
			[
				'case'  => 'Records are in reverse order to their IDs',
				'order' => [4, 3, 2, 1, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 1, 2 => 3, 3 => 2, 4 => 1, 5 => 5],
				],
				'where' => '',
			],
			[
				'case'  => 'Two records have the same order value',
				'order' => [1, 4, 3, 2, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
				],
				'where' => '',
			],
			[
				'case'  => 'All records have no ordering (0)',
				'order' => [1, 2, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 0, 2 => 3, 3 => 8, 4 => 7],
				],
				'where' => '',
			],
			[
				'case'  => 'Records in mixed (non-sequential) order respective to their IDs',
				'order' => [1, 2, 5, 4, 3],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'ordering' => [1 => 1, 2 => 2, 3 => 3, 4 => 4],
				],
				'where' => 'foftest_foobar_id IN(2, 3)',
			],
			[
				'case'  => 'Applying a reorder WHERE clause',
				'order' => [1, 1, 2, 4, 5],
			],
		];

		return $data;
	}

	public static function getTestMove()
	{
		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 1,
				'delta' => -1,
				'where' => '',
			],
			[
				'case'  => 'Move the first record up, no where',
				'order' => [1, 2, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 1,
				'delta' => 0,
				'where' => '',
			],
			[
				'case'  => 'Empty delta',
				'order' => [1, 2, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => -1,
					'where' => null,
				],
				'id'    => 2,
				'delta' => '',
				'where' => '',
			],
			[
				'case'  => 'Move the second record up, no where, delta changed by the event',
				'order' => [2, 1, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 2,
				'delta' => -1,
				'where' => '',
			],
			[
				'case'  => 'Move the second record up, no where',
				'order' => [2, 1, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 2,
				'delta' => 1,
				'where' => '',
			],
			[
				'case'  => 'Move the second record down, no where',
				'order' => [1, 3, 2, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 2,
				'delta' => 1,
				'where' => 'title = "Guinea Pig row"',
			],
			[
				'case'  => 'Move the second record down, with where matching nothing',
				'order' => [1, 2, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => null,
					'where' => null,
				],
				'id'    => 2,
				'delta' => -1,
				'where' => 'title = "Guinea Pig row"',
			],
			[
				'case'  => 'Move the second record up, with where matching one record',
				'order' => [2, 1, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => null,
					'delta' => 'title = "Guinea Pig row"',
					'where' => null,
				],
				'id'    => 2,
				'delta' => -1,
				'where' => '',
			],
			[
				'case'  => 'Move the second record up, where matching one record (changed by the dispatcher)',
				'order' => [2, 1, 3, 4, 5],
			],
		];

		$data[] = [
			[
				'mock'  => [
					'find'  => 2,
					'delta' => null,
					'where' => null,
				],
				'id'    => null,
				'delta' => 1,
				'where' => '',
			],
			[
				'case'  => 'Record loaded by the dispatcher, move the second record down, no where',
				'order' => [1, 3, 2, 4, 5],
			],
		];

		return $data;
	}

	public static function getTestMoveException()
	{
		// Table with no ordering support
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'exception' => 'FOF40\Model\DataModel\Exception\SpecialColumnMissing',
			],
		];

		// Table with no ordering support
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'exception' => 'FOF40\Model\DataModel\Exception\RecordNotLoaded',
			],
		];

		return $data;
	}

	public static function getTestLock()
	{
		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'user_id' => '',
			],
			[
				'case'       => 'Table without locking support',
				'before'     => 0,
				'after'      => 0,
				'dispatcher' => 0,
				'locked_by'  => null,
				'locked_on'  => null,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'user_id' => 90,
			],
			[
				'case'       => 'Table with locking support, user_id passed',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 90,
				'locked_on'  => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => 88,
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'user_id' => null,
			],
			[
				'case'       => 'Table with locking support, user_id not passed',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 88,
				'locked_on'  => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'id',
				'table'   => '#__foftest_lockedby',
				'user_id' => 90,
			],
			[
				'case'       => 'Table with only the locked_by field',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 90,
				'locked_on'  => null,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'id',
				'table'   => '#__foftest_lockedon',
				'user_id' => 90,
			],
			[
				'case'       => 'Table with only the locked_on field',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => null,
				'locked_on'  => true,
			],
		];

		return $data;
	}

	public static function getTestUnlock()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Table without locking support',
				'before'     => 0,
				'after'      => 0,
				'dispatcher' => 0,
				'locked_by'  => null,
				'locked_on'  => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with locking support, user_id passed',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 0,
				'locked_on'  => true,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with locking support, user_id not passed',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 0,
				'locked_on'  => true,
			],
		];

		$data[] = [
			[
				'tableid' => 'id',
				'table'   => '#__foftest_lockedby',
			],
			[
				'case'       => 'Table with only the locked_by field',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => 0,
				'locked_on'  => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'id',
				'table'   => '#__foftest_lockedon',
			],
			[
				'case'       => 'Table with only the locked_on field',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'locked_by'  => null,
				'locked_on'  => true,
			],
		];

		return $data;
	}

	public static function getTestTouch()
	{
		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'user_id' => '',
			],
			[
				'case'        => 'Table without modifying support',
				'modified_by' => null,
				'modified_on' => null,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'user_id' => 90,
			],
			[
				'case'        => 'Table with modifying support, user_id passed',
				'modified_by' => 90,
				'modified_on' => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => 88,
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'user_id' => null,
			],
			[
				'case'        => 'Table with modifying support, user_id not passed',
				'modified_by' => 88,
				'modified_on' => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'id',
				'table'   => '#__foftest_modifiedby',
				'user_id' => 90,
			],
			[
				'case'        => 'Table with only the modified_by field',
				'modified_by' => 90,
				'modified_on' => null,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'user_id' => '',
				],
				'tableid' => 'id',
				'table'   => '#__foftest_modifiedon',
				'user_id' => 90,
			],
			[
				'case'        => 'Table with only the modified_on field',
				'modified_by' => null,
				'modified_on' => true,
			],
		];

		return $data;
	}

	public static function getTestCheckIn()
	{
		// Table not loaded
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'userid'  => null,
				'load'    => 0,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => 'FOF40\Model\DataModel\Exception\RecordNotLoaded',
				'unlock'    => false,
			],
		];

		// Table with no lock support
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'userid'  => null,
				'load'    => 1,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => false,
				'unlock'    => false,
			],
		];

		// Table with only locked_on column
		$data[] = [
			[
				'tableid' => 'id',
				'table'   => '#__foftest_lockedon',
				'userid'  => null,
				'load'    => 1,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record with empty locked_by
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => null,
				'load'    => 1,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by the current user
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 99,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by the current user (userid got from the session)
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => null,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we are admin
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 50,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [
						'com_fakeapp' => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => true,
							'core.manage'     => false,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we are managers
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 50,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [
						'com_fakeapp' => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => true,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we can edit the state
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 50,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [
						'com_fakeapp' => [
							'core.edit.own'   => false,
							'core.edit.state' => true,
							'core.admin'      => false,
							'core.manage'     => false,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we can edit the state (assets tracked)
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 50,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => true,
					'permissions'   => [
						'com_fakeapp'   => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => false,
						],
						'foobars.dummy' => [
							'core.edit.own'   => false,
							'core.edit.state' => true,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we created it and we can edit our own records
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 42,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => false,
					'permissions'   => [
						'com_fakeapp'   => [
							'core.edit.own'   => true,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => false,
						],
						'foobars.dummy' => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we created it and we can edit our own records (assets tracked)
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 42,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => true,
					'permissions'   => [
						'com_fakeapp'   => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => false,
						],
						'foobars.dummy' => [
							'core.edit.own'   => true,
							'core.edit.state' => false,
						],
					],
				],
			],
			[
				'exception' => false,
				'unlock'    => true,
			],
		];

		// Record locked by another user, we created it and but we can not edit our own records
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 42,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => true,
					'permissions'   => [
						'com_fakeapp'   => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => false,
						],
						'foobars.dummy' => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
						],
					],
				],
			],
			[
				'exception' => 'FOF40\Controller\Exception\LockedRecord',
				'unlock'    => false,
			],
		];

		// Record locked by another user, we didn't create it
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'userid'  => 50,
				'load'    => 5,
				'mock'    => [
					'assetsTracked' => true,
					'permissions'   => [
						'com_fakeapp'   => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
							'core.admin'      => false,
							'core.manage'     => false,
						],
						'foobars.dummy' => [
							'core.edit.own'   => false,
							'core.edit.state' => false,
						],
					],
				],
			],
			[
				'exception' => 'FOF40\Controller\Exception\LockedRecord',
				'unlock'    => false,
			],
		];

		return $data;
	}

	public static function getTestIsLocked()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'load'    => 0,
				'userid'  => null,
			],
			[
				'case'   => 'Table with no lock support',
				'result' => false,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 0,
				'userid'  => null,
			],
			[
				'case'   => 'Table with lock support, not loaded',
				'result' => false,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 5,
				'userid'  => null,
			],
			[
				'case'   => 'Table with lock support, loaded, no user provided',
				'result' => true,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 5,
				'userid'  => 99,
			],
			[
				'case'   => 'Table with lock support, loaded, user provided',
				'result' => false,
			],
		];

		return $data;
	}
}
