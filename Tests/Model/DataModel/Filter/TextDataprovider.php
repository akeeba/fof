<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class TextDataprovider
{
	public static function getTestPartial()
	{
		$data[] = [
			[
				'value' => '',
			],
			[
				'case'   => 'Empty value',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value' => 'foobar',
			],
			[
				'case'   => 'Valid value',
				'result' => "(`test` LIKE '%foobar%')",
			],
		];

		return $data;
	}

	public static function getTestExact()
	{
		$data[] = [
			[
				'value' => '',
			],
			[
				'case'   => 'Empty value',
				'result' => '',
			],
		];

		$data[] = [
			[
				'value' => 'foobar',
			],
			[
				'case'   => 'Valid value',
				'result' => "(`test` LIKE 'foobar')",
			],
		];

		return $data;
	}
}
