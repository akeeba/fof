<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class ModelDataprovider
{
	public static function getTest__construct()
	{
		$data[] = [
			[
				'config' => [],
			],
			[
				'case'     => 'State is not set in the config, no populate, no ignore',
				'state'    => (object) [],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'nestedset',
			],
		];

		$data[] = [
			[
				'config' => [
					'name' => 'mocked',
				],
			],
			[
				'case'     => 'State is not set in the config, no populate, no ignore',
				'state'    => (object) [],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'mocked',
			],
		];

		$data[] = [
			[
				'config' => [
					'state' => [
						'dummy' => 'test',
					],
				],
			],
			[
				'case'     => 'State is set in the config (array), no populate, no ignore',
				'state'    => (object) [
					'dummy' => 'test',
				],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'nestedset',
			],
		];

		$data[] = [
			[
				'config' => [
					'state' => 'wrong',
				],
			],
			[
				'case'     => 'State is set in the config (string - wrong), no populate, no ignore',
				'state'    => (object) [],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'nestedset',
			],
		];

		$data[] = [
			[
				'config' => [
					'state' => (object) [
						'dummy' => 'test',
					],
				],
			],
			[
				'case'     => 'State is set in the config (object), no populate, no ignore',
				'state'    => (object) [
					'dummy' => 'test',
				],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'nestedset',
			],
		];

		$data[] = [
			[
				'config' => [
					'state'          => (object) [
						'dummy' => 'test',
					],
					'use_populate'   => true,
					'ignore_request' => true,
				],
			],
			[
				'case'     => 'State is set in the config (object), with populate and ignore',
				'state'    => (object) [
					'dummy' => 'test',
				],
				'populate' => true,
				'ignore'   => true,
				'name'     => 'nestedset',
			],
		];

		$data[] = [
			[
				'config' => [
					'state'          => (object) [
						'dummy' => 'test',
					],
					'use_populate'   => false,
					'ignore_request' => false,
				],
			],
			[
				'case'     => 'State is set in the config (object), with populate and ignore (they are set to false)',
				'state'    => (object) [
					'dummy' => 'test',
				],
				'populate' => false,
				'ignore'   => false,
				'name'     => 'nestedset',
			],
		];

		return $data;
	}

	public static function getTestGetState()
	{
		$data[] = [
			[
				'config'  => [],
				'key'     => '',
				'default' => 'default',
				'filter'  => 'raw',
				'mock'    => [
					'getUserState' => 'user state',
					'ignore'       => false,
				],
			],
			[
				'case'   => 'No key passed',
				'result' => new stdClass(),
			],
		];

		$data[] = [
			[
				'config'  => [
					'state' => [
						'foobar' => 'internal state',
					],
				],
				'key'     => 'foobar',
				'default' => 'default',
				'filter'  => 'raw',
				'mock'    => [
					'getUserState' => 'user state',
					'ignore'       => false,
				],
			],
			[
				'case'   => 'Requesting a key, got it from the internal state',
				'result' => 'internal state',
			],
		];

		$data[] = [
			[
				'config'  => [],
				'key'     => 'foobar',
				'default' => 'default',
				'filter'  => 'raw',
				'mock'    => [
					'getUserState' => 'user state',
					'ignore'       => false,
				],
			],
			[
				'case'   => 'Requesting a key, got it form the request',
				'result' => 'user state',
			],
		];

		$data[] = [
			[
				'config'  => [],
				'key'     => 'foobar',
				'default' => 'default',
				'filter'  => 'raw',
				'mock'    => [
					'getUserState' => null,
					'ignore'       => false,
				],
			],
			[
				'case'   => 'Requesting a key, the request was empty',
				'result' => 'default',
			],
		];

		$data[] = [
			[
				'config'  => [],
				'key'     => 'foobar',
				'default' => 'default',
				'filter'  => 'raw',
				'mock'    => [
					'getUserState' => 'user state',
					'ignore'       => true,
				],
			],
			[
				'case'   => 'Requesting a key, not found in the internal state, ignore flag is on',
				'result' => 'default',
			],
		];

		$data[] = [
			[
				'config'  => [
					'state' => [
						'foobar' => 'internal state',
					],
				],
				'key'     => 'foobar',
				'default' => 'default',
				'filter'  => 'int',
				'mock'    => [
					'getUserState' => 'user state',
					'ignore'       => false,
				],
			],
			[
				'case'   => 'Requesting a key, got it from the internal state, int filter applied',
				'result' => 0,
			],
		];

		return $data;
	}

	public static function getTestSetState()
	{
		$data[] = [
			[
				'property' => 'foo',
				'value'    => 'bar',
				'mock'     => [
					'state' => null,
				],
			],
			[
				'case'   => 'Setting a propery to a value, internal state is empty',
				'result' => 'bar',
				'state'  => (object) [
					'foo' => 'bar',
				],
			],
		];

		$data[] = [
			[
				'property' => 'foo',
				'value'    => 'bar',
				'mock'     => [
					'state' => (object) [
						'dummy' => 'test',
					],
				],
			],
			[
				'case'   => 'Setting a propery to a value, internal state is not empty',
				'result' => 'bar',
				'state'  => (object) [
					'foo'   => 'bar',
					'dummy' => 'test',
				],
			],
		];

		$data[] = [
			[
				'property' => 'foo',
				'value'    => 'bar',
				'mock'     => [
					'state' => (object) [
						'foo' => 'test',
					],
				],
			],
			[
				'case'   => 'Trying to overwrite a propery value, internal state is not empty',
				'result' => 'bar',
				'state'  => (object) [
					'foo' => 'bar',
				],
			],
		];

		return $data;
	}

	public static function getTestSavestate()
	{
		$data[] = [
			[
				'state' => true,
			],
			[
				'case'  => 'New state is boolean true',
				'state' => true,
			],
		];

		$data[] = [
			[
				'state' => false,
			],
			[
				'case'  => 'New state is boolean false',
				'state' => false,
			],
		];

		$data[] = [
			[
				'state' => 1,
			],
			[
				'case'  => 'New state is int 1',
				'state' => true,
			],
		];

		$data[] = [
			[
				'state' => 0,
			],
			[
				'case'  => 'New state is int 0',
				'state' => false,
			],
		];

		return $data;
	}

	public static function getTestPopulatesavestate()
	{
		// Savestate is -999 => we are going to save the state
		$data[] = [
			[
				'state' => -999,
				'mock'  => [
					'state' => null,
				],
			],
			[
				'savestate' => 1,
				'state'     => true,
			],
		];

		// We already saved the state, nothing happens
		$data[] = [
			[
				'state' => -999,
				'mock'  => [
					'state' => true,
				],
			],
			[
				'savestate' => 0,
				'state'     => null,
			],
		];

		// Savestate is 1 => we are going to save the state
		$data[] = [
			[
				'state' => 1,
				'mock'  => [
					'state' => null,
				],
			],
			[
				'savestate' => 1,
				'state'     => 1,
			],
		];

		// Savestate is -1 => we are NOT going to save the state
		$data[] = [
			[
				'state' => -1,
				'mock'  => [
					'state' => null,
				],
			],
			[
				'savestate' => 1,
				'state'     => -1,
			],
		];

		return $data;
	}
}
