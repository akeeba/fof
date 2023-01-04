<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

class BasicFactoryDataprovider
{
	public static function getTestController()
	{
		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Frontend, Foobars, found',
				'result' => 'Fakeapp\Site\Controller\Foobars',
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Frontend, Foobar, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'bare',
			],
			[
				'case'   => 'Frontend, Bare, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Backend, Foobars, found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Backend, Foobar, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'bare',
			],
			[
				'case'   => 'Backend, Bare, not found',
				'result' => \Fakeapp\Admin\Controller\Bare::class,
			],
		];

		return $data;
	}

	public static function getTestModel()
	{
		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Frontend Foobar, found',
				'result' => \Fakeapp\Site\Model\Foobar::class,
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Frontend Foobars, not found',
				'result' => \Fakeapp\Site\Model\Foobar::class,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Backend Foobar, found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Backend Foobars, not found',
				'result' => false,
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
				'case'   => 'Frontend Parents, found',
				'result' => 'Fakeapp\Site\View\Parents\Html',
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'parent',
			],
			[
				'case'   => 'Frontend Parent, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Frontend Foobars, found',
				'result' => 'Fakeapp\Site\View\Foobars\Html',
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Frontend Foobar, not found (will not singularize)',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => false,
				'view'    => 'bare',
			],
			[
				'case'   => 'Frontend Bare, not found',
				'result' => false,
			],
		];


		$data[] = [
			[
				'backend' => true,
				'view'    => 'parents',
			],
			[
				'case'   => 'Backend Parents, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'parent',
			],
			[
				'case'   => 'Backend Parent, not found',
				'result' => false,
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobar',
			],
			[
				'case'   => 'Backend Foobar, found',
				'result' => 'Fakeapp\Admin\View\Foobar\Html',
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'foobars',
			],
			[
				'case'   => 'Backend Foobars, found (will pluralize)',
				'result' => 'Fakeapp\Admin\View\Foobar\Html',
			],
		];

		$data[] = [
			[
				'backend' => true,
				'view'    => 'bare',
			],
			[
				'case'   => 'Backend Bare, found',
				'result' => 'Fakeapp\Admin\View\Bare\Html',
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
				'case'   => 'Frontend dispatcher, not found, fallback to default',
				'result' => 'FOF40\Dispatcher\Dispatcher',
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
				'case'   => 'Backend toolbarnot found, fall back to the standard one',
				'result' => 'FOF40\Toolbar\Toolbar',
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
				'case'   => 'Frontend transparentAuthentication, not found, fall back to the standard one',
				'result' => 'FOF40\TransparentAuthentication\TransparentAuthentication',
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
