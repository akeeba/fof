<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class OwnDataprovider
{
	public static function getTestOnAfterBuildQuery()
	{
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'     => 'Table with created_by column',
				'contains' => true,
				'query'    => "`created_by` = '99'",
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'     => 'Table without created_by column',
				'contains' => false,
				'query'    => "`created_by` = '99'",
			],
		];

		return $data;
	}

	public static function getTestOnAfterLoad()
	{
		// DataModel has not the created_by field
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'mock'    => [
					'created_by' => '',
				],
			],
			[
				'reset' => false,
			],
		];

		// User has access to the data
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'mock'    => [
					'created_by' => 99,
				],
			],
			[
				'reset' => false,
			],
		];

		// No access
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'mock'    => [
					'created_by' => 10,
				],
			],
			[
				'reset' => true,
			],
		];

		return $data;
	}
}
