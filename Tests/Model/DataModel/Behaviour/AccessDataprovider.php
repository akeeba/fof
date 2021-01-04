<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class AccessDataprovider
{
	public static function getTestOnAfterBuildQuery()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'access' => false,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'access' => true,
			],
		];

		return $data;
	}

	public static function getTestOnAfterLoad()
	{
		// DataModel has not the access field
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'mock'    => [
					'userAccess' => '',
					'access'     => '',
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
					'userAccess' => [10, 5, 1],
					'access'     => 10,
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
					'userAccess' => [5, 1],
					'access'     => 10,
				],
			],
			[
				'reset' => true,
			],
		];

		return $data;
	}
}
