<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class BasicFactoryDataprovider
{
	public static function getTestController()
	{
		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [true],
				],
			],
			[
				'case'      => 'Controller is immediately found',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\Controller\Foobars'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ControllerNotFound',
						true,
					],
				],
			],
			[
				'case'      => 'Controller is found at second try',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\Controller\Foobars', '\Fakeapp\Site\Controller\Foobar'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ControllerNotFound',
						'FOF40\Factory\Exception\ControllerNotFound',
					],
				],
			],
			[
				'case'      => 'Controller is not found',
				'exception' => 'FOF40\Factory\Exception\ControllerNotFound',
				'names'     => [],
			],
		];

		return $data;
	}

	public static function getTestModel()
	{
		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [true],
				],
			],
			[
				'case'      => 'Model is immediately found',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\Model\Foobars'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ModelNotFound',
						true,
					],
				],
			],
			[
				'case'      => 'Model is found at second try',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\Model\Foobars', '\Fakeapp\Site\Model\Foobar'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ModelNotFound',
						'FOF40\Factory\Exception\ModelNotFound',
					],
				],
			],
			[
				'case'      => 'Model is not found',
				'exception' => 'FOF40\Factory\Exception\ModelNotFound',
				'names'     => [],
			],
		];

		return $data;
	}

	public static function getTestView()
	{
		$data[] = [
			[
				'view' => 'foobars',
				'type' => 'html',
				'mock' => [
					'create' => [true],
				],
			],
			[
				'case'      => 'View is immediately found',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\View\Foobars\Html'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'type' => 'json',
				'mock' => [
					'create' => [true],
				],
			],
			[
				'case'      => 'View is immediately found, JSON type',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\View\Foobars\Json'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'type' => 'html',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ViewNotFound',
						true,
					],
				],
			],
			[
				'case'      => 'View is found at second try',
				'exception' => '',
				'names'     => ['\Fakeapp\Site\View\Foobars\Html', '\Fakeapp\Site\View\Foobar\Html'],
			],
		];

		$data[] = [
			[
				'view' => 'foobars',
				'type' => 'html',
				'mock' => [
					'create' => [
						'FOF40\Factory\Exception\ViewNotFound',
						'FOF40\Factory\Exception\ViewNotFound',
					],
				],
			],
			[
				'case'      => 'View is not found',
				'exception' => 'FOF40\Factory\Exception\ViewNotFound',
				'names'     => [],
			],
		];

		return $data;
	}

	public static function getTestDispatcher()
	{
		$data[] = [
			[
				'mock' => [
					'create' => true,
				],
			],
			[
				'case'   => 'Dispatcher found',
				'name'   => '\Fakeapp\Site\Dispatcher\Dispatcher',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'create' => 'FOF40\Factory\Exception\DispatcherNotFound',
				],
			],
			[
				'case'   => 'Dispatcher not found, fall back to the default one',
				'name'   => '\Fakeapp\Site\Dispatcher\Dispatcher',
				'result' => true,
			],
		];

		return $data;
	}

	public static function getTestToolbar()
	{
		$data[] = [
			[
				'mock' => [
					'create' => true,
				],
			],
			[
				'case'   => 'Toolbar found',
				'name'   => '\Fakeapp\Site\Toolbar\Toolbar',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'create' => 'FOF40\Factory\Exception\ToolbarNotFound',
				],
			],
			[
				'case'   => 'Toolbar not found, fall back to the default one',
				'name'   => '\Fakeapp\Site\Toolbar\Toolbar',
				'result' => true,
			],
		];

		return $data;
	}

	public static function getTestTransparentAuthentication()
	{
		$data[] = [
			[
				'mock' => [
					'create' => true,
				],
			],
			[
				'case'   => 'Authentication found',
				'name'   => '\Fakeapp\Site\TransparentAuthentication\TransparentAuthentication',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'create' => 'FOF40\Factory\Exception\TransparentAuthenticationNotFound',
				],
			],
			[
				'case'   => 'Authentication not found, fall back to the default one',
				'name'   => '\Fakeapp\Site\TransparentAuthentication\TransparentAuthentication',
				'result' => true,
			],
		];

		return $data;
	}

	public static function getTestForm()
	{
		$data[] = [
			[
				'mock'    => [
					'formFilename' => false,
					'scaffolding'  => false,
				],
				'name'    => 'item',
				'source'  => 'form.default',
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'No scaffolding, no filename, source is the name of the form',
				'result'    => null,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'formFilename' => false,
					'scaffolding'  => true,
				],
				'name'    => 'item',
				'source'  => 'form.default',
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'With scaffolding, no filename, source is the name of the form',
				'result'    => true,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'formFilename' => JPATH_TESTS . '/_data/form/form.default.xml',
					'scaffolding'  => true,
				],
				'name'    => 'item',
				'source'  => 'form.default',
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'With scaffolding, with filename, source is the name of the form',
				'result'    => true,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'formFilename' => 'wrong_path',
					'scaffolding'  => true,
				],
				'name'    => 'item',
				'source'  => 'form.default',
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'With scaffolding, with filename (not existing), source is the name of the form',
				'result'    => true,
				'exception' => 'FOF40\Factory\Exception\FormLoadFile',
			],
		];

		$data[] = [
			[
				'mock'    => [
					'formFilename' => 'wrong_path',
					'scaffolding'  => true,
				],
				'name'    => 'item',
				'source'  => file_get_contents(JPATH_TESTS . '/_data/form/form.default.xml'),
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'With scaffolding, no filename, source is a correct string',
				'result'    => true,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'formFilename' => 'wrong_path',
					'scaffolding'  => true,
				],
				'name'    => 'item',
				'source'  => '<form this is simply wrong',
				'view'    => 'children',
				'options' => [],
				'replace' => true,
				'xpath'   => false,
			],
			[
				'case'      => 'With scaffolding, no filename, source is a malformed string',
				'result'    => true,
				'exception' => 'FOF40\Factory\Exception\FormLoadData',
			],
		];

		return $data;
	}
}
