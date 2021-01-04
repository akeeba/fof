<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class NumberDataprovider
{
	public static function getTestSearch()
	{
		$data = [];

		$data[] = [
			[
				'value'    => 1,
				'operator' => '=',
			],
			[
				'case'   => 'Equals integer',
				'result' => '(`test` = \'1\')',
			],
		];

		$data[] = [
			[
				'value'    => 1.2,
				'operator' => '=',
			],
			[
				'case'   => 'Equals float',
				'result' => '(`test` = \'1.2\')',
			],
		];

		return $data;
	}

	public static function getTestBetween()
	{
		$data[] = [
			[
				'from'      => null,
				'to'        => null,
				'inclusive' => false,
			],
			[
				'case'   => 'From and to are null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => null,
				'to'        => 5,
				'inclusive' => false,
			],
			[
				'case'   => 'From is null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 5,
				'to'        => null,
				'inclusive' => false,
			],
			[
				'case'   => 'To is null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 1,
				'to'        => 5,
				'inclusive' => false,
			],
			[
				'case'   => 'From and to are set, not inclusive ',
				'result' => '((`test` > 1) AND (`test` < 5))',
			],
		];

		$data[] = [
			[
				'from'      => 1,
				'to'        => 5,
				'inclusive' => true,
			],
			[
				'case'   => 'From and to are set, inclusive ',
				'result' => '((`test` >= 1) AND (`test` <= 5))',
			],
		];

		$data[] = [
			[
				'from'      => -5,
				'to'        => -1,
				'inclusive' => true,
			],
			[
				'case'   => 'From and to are set and they are negative, inclusive ',
				'result' => '((`test` >= -5) AND (`test` <= -1))',
			],
		];

		$data[] = [
			[
				'from'      => -5,
				'to'        => 0,
				'inclusive' => true,
			],
			[
				'case'   => 'From is negative and to is 0, inclusive ',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 4.2,
				'to'        => 6.6,
				'inclusive' => true,
			],
			[
				'case'   => 'Floats provided',
				'result' => '((`test` >= 4.2) AND (`test` <= 6.6))',
			],
		];

		$data[] = [
			[
				'from'      => 4.2,
				'to'        => 'test',
				'inclusive' => true,
			],
			[
				'case'   => 'Wrong $to argument',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 'test',
				'to'        => 4.2,
				'inclusive' => true,
			],
			[
				'case'   => 'Wrong $from argument',
				'result' => '',
			],
		];

		return $data;
	}

	public static function getTestOutside()
	{
		$data[] = [
			[
				'from'      => null,
				'to'        => null,
				'inclusive' => false,
			],
			[
				'case'   => 'From and to are null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => null,
				'to'        => 5,
				'inclusive' => false,
			],
			[
				'case'   => 'From is null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 5,
				'to'        => null,
				'inclusive' => false,
			],
			[
				'case'   => 'To is null',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 1,
				'to'        => 5,
				'inclusive' => false,
			],
			[
				'case'   => 'From and to are set, not inclusive ',
				'result' => '((`test` < 1) OR (`test` > 5))',
			],
		];

		$data[] = [
			[
				'from'      => 1,
				'to'        => 5,
				'inclusive' => true,
			],
			[
				'case'   => 'From and to are set, inclusive ',
				'result' => '((`test` <= 1) OR (`test` >= 5))',
			],
		];

		$data[] = [
			[
				'from'      => -5,
				'to'        => -1,
				'inclusive' => true,
			],
			[
				'case'   => 'From and to are set and they are negative, inclusive ',
				'result' => '((`test` <= -5) OR (`test` >= -1))',
			],
		];

		$data[] = [
			[
				'from'      => -5,
				'to'        => 0,
				'inclusive' => true,
			],
			[
				'case'   => 'From is negative and to is 0, inclusive ',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 4.2,
				'to'        => 6.6,
				'inclusive' => true,
			],
			[
				'case'   => 'Floats provided',
				'result' => '((`test` <= 4.2) OR (`test` >= 6.6))',
			],
		];

		$data[] = [
			[
				'from'      => 4.2,
				'to'        => 'test',
				'inclusive' => true,
			],
			[
				'case'   => 'Wrong $to argument',
				'result' => '',
			],
		];

		$data[] = [
			[
				'from'      => 'test',
				'to'        => 4.2,
				'inclusive' => true,
			],
			[
				'case'   => 'Wrong $from argument',
				'result' => '',
			],
		];

		return $data;
	}

	public static function getTestInterval()
	{
		$data[] = [
			[
				'value'     => null,
				'interval'  => 1,
				'inclusive' => false,
			],
			[
				'case'   => 'Value is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value'     => 5,
				'interval'  => 1,
				'inclusive' => false,
			],
			[
				'case'   => 'Value is not empty, not inclusive',
				'result' => '((`test` > 4) AND (`test` < 6))',
			],
		];

		$data[] = [
			[
				'value'     => 5,
				'interval'  => 1,
				'inclusive' => true,
			],
			[
				'case'   => 'Value is not empty, inclusive',
				'result' => '((`test` >= 4) AND (`test` <= 6))',
			],
		];

		$data[] = [
			[
				'value'     => 3.2,
				'interval'  => 1.2,
				'inclusive' => true,
			],
			[
				'case'   => 'Float values provided',
				'result' => '((`test` >= 2) AND (`test` <= 4.4))',
			],
		];

		$data[] = [
			[
				'value'     => 3.2,
				'interval'  => 'test',
				'inclusive' => true,
			],
			[
				'case'   => 'Wrong interval type',
				'result' => '((`test` >= 3.2) AND (`test` <= 3.2))',
			],
		];

		return $data;
	}
}
