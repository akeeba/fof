<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class EnabledDataprovider
{
	public static function getTestOnBeforeBuildQuery()
	{
		$data[] = [
			[
				'table'   => '#__foftest_bares',
				'tableid' => 'foftest_bare_id',
			],
			[
				'case'  => 'Table without enabled field',
				'count' => 0,
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
			],
			[
				'case'  => 'Table with enabled field',
				'count' => 1,
			],
		];

		return $data;
	}

	public static function getTestOnAfterLoad()
	{
		// DataModel has not the enabled field
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'mock'    => [
					'enabled' => 0,
				],
			],
			[
				'reset' => false,
			],
		];

		// Enabled
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'mock'    => [
					'enabled' => 1,
				],
			],
			[
				'reset' => false,
			],
		];

		// Not enabled
		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'mock'    => [
					'enabled' => 0,
				],
			],
			[
				'reset' => true,
			],
		];

		return $data;
	}
}
