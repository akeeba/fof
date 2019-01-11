<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class DateDataprovider
{
	public static function getTestBetween()
	{
		$data[] = [
			[
				'from'    => '',
				'to'      => '',
				'include' => false,
			],
			[
				'case'   => 'From and to are empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '',
				'include' => false,
			],
			[
				'case'   => 'To is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '',
				'to'      => '1980-01-01',
				'include' => false,
			],
			[
				'case'   => 'From is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '1980-02-01',
				'include' => false,
			],
			[
				'case'   => 'From/to are set, not inclusive',
				'result' => "((`test` > '1980-01-01') AND (`test` < '1980-02-01'))",
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '1980-02-01',
				'include' => true,
			],
			[
				'case'   => 'From/to are set, inclusive',
				'result' => "((`test` >= '1980-01-01') AND (`test` <= '1980-02-01'))",
			],
		];

		return $data;
	}

	public static function getTestOutside()
	{
		$data[] = [
			[
				'from'    => '',
				'to'      => '',
				'include' => false,
			],
			[
				'case'   => 'From and to are empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '',
				'include' => false,
			],
			[
				'case'   => 'To is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '',
				'to'      => '1980-01-01',
				'include' => false,
			],
			[
				'case'   => 'From is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '1980-02-01',
				'include' => false,
			],
			[
				'case'   => 'From/to are set, not inclusive',
				'result' => "((`test` < '1980-01-01') AND (`test` > '1980-02-01'))",
			],
		];

		$data[] = [
			[
				'from'    => '1980-01-01',
				'to'      => '1980-02-01',
				'include' => true,
			],
			[
				'case'   => 'From/to are set, inclusive',
				'result' => "((`test` <= '1980-01-01') AND (`test` >= '1980-02-01'))",
			],
		];

		return $data;
	}

	public static function getTestInterval()
	{
		$data[] = [
			[
				'value'    => '',
				'interval' => '',
				'include'  => false,
			],
			[
				'case'   => 'Value/interval are empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '',
				'interval' => '+1 MONTH',
				'include'  => false,
			],
			[
				'case'   => 'Value is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => '',
				'include'  => false,
			],
			[
				'case'   => 'Interval is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => '+1 MONTH',
				'include'  => false,
			],
			[
				'case'   => 'Value and interval are set, non inclusive',
				'result' => '(`test` > DATE_ADD(`test`, INTERVAL 1 MONTH))',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => '+1 MONTH',
				'include'  => true,
			],
			[
				'case'   => 'Value and interval are set, inclusive',
				'result' => '(`test` >= DATE_ADD(`test`, INTERVAL 1 MONTH))',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => [
					'value' => 1,
					'unit'  => 'MONTH',
					'sign'  => '-',
				],
				'include'  => true,
			],
			[
				'case'   => 'Interval is an array',
				'result' => '(`test` >= DATE_SUB(`test`, INTERVAL 1 MONTH))',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => (object) [
					'value' => 1,
					'unit'  => 'MONTH',
					'sign'  => '-',
				],
				'include'  => true,
			],
			[
				'case'   => 'Interval is an object',
				'result' => '(`test` >= DATE_SUB(`test`, INTERVAL 1 MONTH))',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => [
					'unit' => 'MONTH',
					'sign' => '-',
				],
				'include'  => true,
			],
			[
				'case'   => 'Incomplete interval array',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => [
					'value' => 1,
					'sign'  => '-',
				],
				'include'  => true,
			],
			[
				'case'   => 'Incomplete interval array',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => [
					'value' => 1,
					'unit'  => 'MONTH',
				],
				'include'  => true,
			],
			[
				'case'   => 'Incomplete interval array',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => '-1',
				'include'  => true,
			],
			[
				'case'   => 'Incomplete interval string',
				'result' => '(`test` >= DATE_ADD(`test`, INTERVAL 1 MONTH))',
			],
		];

		$data[] = [
			[
				'value'    => '2014-31-23',
				'interval' => '1',
				'include'  => true,
			],
			[
				'case'   => 'Incomplete interval string',
				'result' => '(`test` >= DATE_ADD(`test`, INTERVAL 1 MONTH))',
			],
		];

		return $data;
	}
}
