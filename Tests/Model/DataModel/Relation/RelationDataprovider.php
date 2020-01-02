<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class RelationDataprovider
{
	public function getTestGetData()
	{
		$data[] = [
			[
				'mock' => [
					'filter' => true,
					'data'   => null,
				],
			],
			[
				'case'          => 'Data is filtered',
				'applyCallback' => true,
				'count'         => 3,
			],
		];

		$data[] = [
			[
				'mock' => [
					'filter' => false,
					'data'   => null,
				],
			],
			[
				'case'          => 'Data is not filtered',
				'applyCallback' => false,
				'count'         => 0,
			],
		];

		$data[] = [
			[
				'mock' => [
					'filter' => true,
					'data'   => new \FOF40\Model\DataModel\Collection([1]),
				],
			],
			[
				'case'          => 'Data fetched from the internal cache',
				'applyCallback' => false,
				'count'         => 1,
			],
		];

		return $data;
	}
}
