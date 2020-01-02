<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class CollectionDataprovider
{
	public static function getTestFind()
	{
		$data[] = [
			[
				'key'     => 1,
				'default' => null,
			],
			[
				'case'   => 'Loading using a key, item found',
				'type'   => 'object',
				'result' => 1,
			],
		];

		$data[] = [
			[
				'key'     => 100,
				'default' => 5,
			],
			[
				'case'   => 'Loading using a key, item not found',
				'type'   => 'int',
				'result' => 5,
			],
		];

		$data[] = [
			[
				'key'     => 'object',
				'default' => null,
			],
			[
				'case'   => 'Loading using a model, item found',
				'type'   => 'object',
				'result' => 2,
			],
		];

		return $data;
	}

	public static function getTestRemoveById()
	{
		$data[] = [
			[
				'key' => 1,
			],
			[
				'case' => 'Removed using a key',
				'key'  => 1,
			],
		];

		$data[] = [
			[
				'key' => 'object',
			],
			[
				'case' => 'Removed using a model',
				'key'  => 2,
			],
		];

		return $data;
	}

	public static function getTestContains()
	{
		$data[] = [
			[
				'key' => 1,
			],
			[
				'case'   => 'Key is contained',
				'result' => true,
			],
		];

		$data[] = [
			[
				'key' => 100,
			],
			[
				'case'   => 'Key is not contained',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTest__call()
	{
		$data[] = [
			[
				'arguments' => 0,
				'load'      => 0,
			],
			[
				'case' => 'Empty collection',
				'call' => null,
			],
		];

		$data[] = [
			[
				'arguments' => 0,
				'load'      => 1,
			],
			[
				'case' => 'Passing no arguments',
				'call' => [
					[],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 1,
				'load'      => 1,
			],
			[
				'case' => 'Passing 1 argument',
				'call' => [
					[1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 2,
				'load'      => 1,
			],
			[
				'case' => 'Passing 2 arguments',
				'call' => [
					[1, 1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 3,
				'load'      => 1,
			],
			[
				'case' => 'Passing 3 arguments',
				'call' => [
					[1, 1, 1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 4,
				'load'      => 1,
			],
			[
				'case' => 'Passing 4 arguments',
				'call' => [
					[1, 1, 1, 1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 5,
				'load'      => 1,
			],
			[
				'case' => 'Passing 5 arguments',
				'call' => [
					[1, 1, 1, 1, 1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 6,
				'load'      => 1,
			],
			[
				'case' => 'Passing no arguments',
				'call' => [
					[1, 1, 1, 1, 1, 1],
				],
			],
		];

		$data[] = [
			[
				'arguments' => 7,
				'load'      => 1,
			],
			[
				'case' => 'Passing 7 arguments',
				'call' => [
					[1, 1, 1, 1, 1, 1, 1],
				],
			],
		];

		return $data;
	}
}
