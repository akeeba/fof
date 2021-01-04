<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class SwitchFactoryDataprovider
{
	public static function getTestController()
	{
		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Frontend controller, found',
				'result' => 'Fakeapp\Site\Controller\Foobars',
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Backend controller, found',
				'result' => 'Fakeapp\Site\Controller\Foobars',
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'bares',
			],
			[
				'case'   => 'Backend controller, found',
				'result' => 'Fakeapp\Admin\Controller\Bare',
			],
		];

		return $data;
	}

	public static function getTestModel()
	{
		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Frontend model, found',
				'result' => 'Fakeapp\Site\Model\Foobar',
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Backend model, found',
				'result' => 'Fakeapp\Site\Model\Foobar',
			],
		];

		return $data;
	}

	public static function getTestView()
	{
		$data[] = [
			[
				'backend' => false,
				'view'    => 'parents',
			],
			[
				'case'   => 'Frontend view, found',
				'result' => 'Fakeapp\Site\View\Parents\Html',
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'bares',
			],
			[
				'case'   => 'Frontend view, found',
				'result' => 'Fakeapp\Admin\View\Bare\Html',
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'parents',
			],
			[
				'case'   => 'Backend view, found',
				'result' => 'Fakeapp\Site\View\Parents\Html',
			],
		];

		return $data;
	}

	public static function getTestDispatcher()
	{
		$data[] = [
			[
				'backend'   => false,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Frontend dispatcher, found',
				'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Backend dispatcher, found',
				'result' => 'Fakeapp\Admin\Dispatcher\Dispatcher',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_dummyapp',
			],
			[
				'case'   => 'Dispatcher not found, fall back to the standard one',
				'result' => 'FOF40\Dispatcher\Dispatcher',
			],
		];

		return $data;
	}

	public static function getTestToolbar()
	{
		$data[] = [
			[
				'backend'   => false,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Frontend toolbar, found',
				'result' => 'Fakeapp\Site\Toolbar\Toolbar',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Backend toolbar, found',
				'result' => 'Fakeapp\Site\Toolbar\Toolbar',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_dummyapp',
			],
			[
				'case'   => 'Toolbar not found, fall back to the standard one',
				'result' => 'FOF40\Toolbar\Toolbar',
			],
		];

		return $data;
	}

	public static function getTestTransparentAuthentication()
	{
		$data[] = [
			[
				'backend'   => false,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Frontend transparentAuthentication, found',
				'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_fakeapp',
			],
			[
				'case'   => 'Backend transparentAuthentication, found',
				'result' => 'Fakeapp\Admin\TransparentAuthentication\TransparentAuthentication',
			],
		];

		$data[] = [
			[
				'backend'   => true,
				'component' => 'com_dummyapp',
			],
			[
				'case'   => 'TransparentAuthentication not found, fall back to the standard one',
				'result' => 'FOF40\TransparentAuthentication\TransparentAuthentication',
			],
		];

		return $data;
	}
}
