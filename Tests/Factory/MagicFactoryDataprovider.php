<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class MagicFactoryDataprovider
{
	public static function getTestController()
	{
		$data[] = [
			[
				'view' => 'foobars',
			],
			[
				'case'   => 'Controller is immediately found',
				'result' => 'Fakeapp\Site\Controller\Foobars',
			],
		];

		$data[] = [
			[
				'view' => 'nothere',
			],
			[
				'case'   => 'Controller is not found',
				'result' => 'Fakeapp\Site\Controller\DefaultDataController',
			],
		];

		return $data;
	}

	public static function getTestModel()
	{
		$data[] = [
			[
				'view' => 'foobars',
			],
			[
				'case'   => 'Model is immediately found',
				'result' => 'Fakeapp\Site\Model\Foobar',
			],
		];

		$data[] = [
			[
				'view' => 'tests',
			],
			[
				'case'   => 'Model is not found',
				'result' => 'FOF40\Model\DataModel',
			],
		];

		return $data;
	}

	public static function getTestView()
	{
		$data[] = [
			[
				'view' => 'foobars',
			],
			[
				'case'   => 'View is immediately found',
				'result' => 'Fakeapp\Site\View\Foobars\Html',
			],
		];

		$data[] = [
			[
				'view' => 'tests',
			],
			[
				'case'   => 'View is not found',
				'result' => 'FOF40\View\DataView\Html',
			],
		];

		return $data;
	}

	public static function getTestDispatcher()
	{
		$data[] = [
			[
				'backend' => true,
			],
			[
				'case'   => 'Dispatcher is found',
				'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher',
			],
		];

		$data[] = [
			[
				'backend' => false,
			],
			[
				'case'   => 'Dispatcher not found',
				'result' => 'FOF40\Dispatcher\Dispatcher',
			],
		];

		return $data;
	}

	public static function getTestTransparentAuthentication()
	{
		$data[] = [
			[
				'backend' => true,
			],
			[
				'case'   => 'TransparentAuthentication is found',
				'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication',
			],
		];

		$data[] = [
			[
				'backend' => false,
			],
			[
				'case'   => 'TransparentAuthentication not found',
				'result' => 'FOF40\TransparentAuthentication\TransparentAuthentication',
			],
		];

		return $data;
	}
}
