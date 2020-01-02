<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class DispatcherDataprovider
{
	public static function getTest__construct()
	{
		$data['Nothing passed in the input, no default view'] = [
			[
				'mock' => [
					'defaultView' => null,
					'input'       => [],
				],
			],
			[
				'case'          => 'Nothing passed in the input, no default view',
				'defaultView'   => null,
				'view'          => null,
				'layout'        => '',
				'containerView' => null,
			],
		];

		$data['Nothing passed in the input, with default view'] = [
			[
				'mock' => [
					'defaultView' => 'test',
					'input'       => [],
				],
			],
			[
				'case'          => 'Nothing passed in the input, with default view',
				'defaultView'   => 'test',
				'view'          => 'test',
				'layout'        => '',
				'containerView' => 'test',
			],
		];

		$data['Data passed in the input, no default view'] = [
			[
				'mock' => [
					'defaultView' => null,
					'input'       => [
						'view'   => 'foobars',
						'layout' => 'default',
					],
				],
			],
			[
				'case'          => 'Data passed in the input, no default view',
				'defaultView'   => null,
				'view'          => 'foobars',
				'layout'        => 'default',
				'containerView' => 'foobars',
			],
		];

		return $data;
	}

	public static function getTest__get()
	{
		$data[] = [
			[
				'method' => 'input',
			],
			[
				'case'   => 'Requesting the input object from the container',
				'result' => true,
			],
		];

		$data[] = [
			[
				'method' => 'wrong',
			],
			[
				'case'   => 'Requesting a non-existing property',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestDispatch()
	{
		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => true,
					'after'   => true,
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, everything went fine',
				'exception' => false,
				'events'    => ['before' => 1, 'after' => 1, 'beforeCli' => 0, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => 'throw',
					'after'   => true,
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, onBefore throws an exception',
				'exception' => true,
				'events'    => ['before' => 1, 'after' => 0, 'beforeCli' => 0, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => false,
					'before'  => true,
					'after'   => true,
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, execute returns false',
				'exception' => true,
				'events'    => ['before' => 1, 'after' => 0, 'beforeCli' => 0, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => true,
					'after'   => 'throw',
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, onAfter returns false',
				'exception' => true,
				'events'    => ['before' => 1, 'after' => 1, 'beforeCli' => 0, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => true,
					'after'   => true,
					'isCli'   => true,
				],
			],
			[
				'case'      => 'In CLI, everything went fine',
				'exception' => false,
				'events'    => ['before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 1],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => 'throw',
					'after'   => true,
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, onBefore throws an exception',
				'exception' => true,
				'events'    => ['before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => false,
					'before'  => true,
					'after'   => true,
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, execute returns false',
				'exception' => true,
				'events'    => ['before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 0],
			],
		];

		$data[] = [
			[
				'mock' => [
					'input'   => [],
					'execute' => true,
					'before'  => true,
					'after'   => 'throw',
					'isCli'   => false,
				],
			],
			[
				'case'      => 'Not in CLI, onAfter returns false',
				'exception' => true,
				'events'    => ['before' => 0, 'after' => 0, 'beforeCli' => 1, 'afterCli' => 1],
			],
		];

		return $data;
	}
}
