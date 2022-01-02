<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class MagicSwitchFactoryDataprovider
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
				'backend'      => true,
				'backend_path' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
				'component'    => 'com_fakeapp',
			],
			[
				'case'   => 'Dispatcher is found',
				'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher',
			],
		];

		$data[] = [
			[
				'backend'      => false,
				'backend_path' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
				'component'    => 'com_fakeapp',
			],
			[
				'case'   => 'Dispatcher is found',
				'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher',
			],
		];

		$data[] = [
			[
				'backend'      => false,
				'backend_path' => JPATH_TESTS . '/Stubs/Dummyapp/Admin',
				'component'    => 'com_dummyapp',
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
				'backend'      => true,
				'backend_path' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
				'component'    => 'com_fakeapp',
			],
			[
				'case'   => 'TransparentAuthentication is found',
				'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication',
			],
		];

		$data[] = [
			[
				'backend'      => false,
				'backend_path' => JPATH_TESTS . '/Stubs/Fakeapp/Admin',
				'component'    => 'com_fakeapp',
			],
			[
				'case'   => 'TransparentAuthentication is found',
				'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication',
			],
		];

		$data[] = [
			[
				'backend'      => false,
				'backend_path' => JPATH_TESTS . '/Stubs/Dummyapp/Admin',
				'component'    => 'com_dummyapp',
			],
			[
				'case'   => 'TransparentAuthentication not found',
				'result' => 'FOF40\TransparentAuthentication\TransparentAuthentication',
			],
		];

		return $data;
	}
}
