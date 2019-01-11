<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class PageParametersToStateDataprovider
{
	public static function getTestOnAfterConstruct()
	{
		$data[] = [
			'input' => [
				'input'  => [],
				'mock'   => [
					'admin' => true,
				],
				'state'  => [
					'foo' => 'bar',
				],
				'params' => [],
			],
			'check' => [
				'case'  => 'We are on the backend',
				'state' => [
					'foo' => 'bar',
				],
			],
		];

		$data[] = [
			'input' => [
				'input'  => [],
				'mock'   => [
					'admin' => false,
				],
				'state'  => [
					'foo' => 'bar',
				],
				'params' => [],
			],
			'check' => [
				'case'  => 'Page params are empty',
				'state' => [
					'foo' => 'bar',
				],
			],
		];

		$data[] = [
			'input' => [
				'input'  => [],
				'mock'   => [
					'admin' => false,
				],
				'state'  => [
					'foo' => 'bar',
				],
				'params' => [
					'hello' => 'world',
				],
			],
			'check' => [
				'case'  => 'Page params not present inside model state',
				'state' => [
					'foo'   => 'bar',
					'hello' => 'world',
				],
			],
		];

		$data[] = [
			'input' => [
				'input'  => [],
				'mock'   => [
					'admin' => false,
				],
				'state'  => [
					'foo' => 'bar',
				],
				'params' => [
					'foo' => 'new',
				],
			],
			'check' => [
				'case'  => 'Param already set in the model state',
				'state' => [
					'foo' => 'bar',
				],
			],
		];

		$data[] = [
			'input' => [
				'input'  => [],
				'mock'   => [
					'admin' => false,
				],
				'state'  => [
					'foo' => 0,
				],
				'params' => [
					'foo' => 'new',
				],
			],
			'check' => [
				'case'  => 'Param already set in the model state (empty value)',
				'state' => [
					'foo' => 0,
				],
			],
		];

		$data[] = [
			'input' => [
				'input'  => [
					'foo' => 'bar',
				],
				'mock'   => [
					'admin' => false,
				],
				'state'  => [],
				'params' => [
					'foo' => 'new',
				],
			],
			'check' => [
				'case'  => 'Param already set in the input',
				'state' => [
					'foo' => 'bar',
				],
			],
		];

		return $data;
	}
}
