<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class FiltersDataprovider
{
	public static function getTestOnAfterBuildQuery()
	{
		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'id' => 1,
					],
				],
			],
			[
				'case'  => 'Searching against primary key, not ignoring the request',
				'query' => "SELECT *
FROM test",
			],
		];

		$data[] = [
			[
				'ignore' => true,
				'mock'   => [
					'state' => [
						'id' => 1,
					],
				],
			],
			[
				'case'  => 'Searching against primary key, ignoring the request',
				'query' => "SELECT *
FROM test
WHERE (`foftest_foobar_id` = '1')",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'title' => 'test',
					],
				],
			],
			[
				'case'  => 'Searching against text field',
				'query' => "SELECT *
FROM test
WHERE (`title` LIKE '%test%')",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'title' => [
							'value' => 'one',
						],
					],
				],
			],
			[
				'case'  => 'Searching text using an array, results in LIKE query',
				'query' => "SELECT *
FROM test
WHERE (`title` LIKE '%one%')",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'from' => '1979-01-01',
							'to'   => '1981-12-31',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, value key not present (invalid)',
				'query' => "SELECT *
FROM test",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method' => 'between',
							'from'   => '1979-01-01',
							'to'     => '1981-12-31',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - between dates',
				'query' => "SELECT *
FROM test
WHERE ((`created_on` > '1979-01-01') AND (`created_on` < '1981-12-31'))",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method' => 'between',
							'to'     => '1981-12-31',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - between dates, no lower limit (invalid)',
				'query' => "SELECT *
FROM test",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method' => 'between',
							'from'   => '1979-01-01',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - between dates, no upper limit (invalid)',
				'query' => "SELECT *
FROM test",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method' => 'outside',
							'from'   => '1979-01-01',
							'to'     => '1981-12-31',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - outside dates',
				'query' => "SELECT *
FROM test
WHERE ((`created_on` < '1979-01-01') AND (`created_on` > '1981-12-31'))",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method'   => 'interval',
							'value'    => '1979-01-01',
							'interval' => '+1 year',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - date interval',
				'query' => "SELECT *
FROM test
WHERE (`created_on` >= DATE_ADD(`created_on`, INTERVAL 1 year))",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method' => 'search',
							'value'  => '1979-01-01',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - search date',
				'query' => "SELECT *
FROM test
WHERE (`created_on` = '1979-01-01')",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method'  => 'between',
							'from'    => '1979-01-01',
							'to'      => '1981-12-31',
							'include' => true,
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method in the state - between dates, inclusive',
				'query' => "SELECT *
FROM test
WHERE ((`created_on` >= '1979-01-01') AND (`created_on` <= '1981-12-31'))",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'id' => [
							'method' => 'wrong',
							'value'  => '32',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing a wrong method in the state',
				'query' => "SELECT *
FROM test",
			],
		];

		$data[] = [
			[
				'ignore' => false,
				'mock'   => [
					'state' => [
						'created_on' => [
							'method'   => 'search',
							'operator' => '>',
							'value'    => '1979-01-01',
						],
					],
				],
			],
			[
				'case'  => 'Searching using an array, passing the method and operator in the state',
				'query' => "SELECT *
FROM test
WHERE (`created_on` > '1979-01-01')",
			],
		];

		return $data;
	}
}
