<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class ToolbarDataprovider
{
	public static function getTestRenderToolbar()
	{
		$data[] = [
			//test
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => '',
					'config'        => '',
				],
				'input'     => [
					'tmpl' => 'component',
				],
				'useConfig' => false,
				'view'      => null,
				'task'      => null,
			],
			//check
			[
				'case'    => 'Component template, no render_toolbar flag',
				'config'  => '',
				'counter' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => '',
					'config'        => '',
				],
				'input'     => [
					'tmpl'           => '',
					'render_toolbar' => 0,
				],
				'useConfig' => false,
				'view'      => null,
				'task'      => null,
			],
			[
				'case'    => 'No template, render_toolbar flag set to off',
				'config'  => '',
				'counter' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => null,
					'config'        => '',
				],
				'input'     => [],
				'useConfig' => false,
				'view'      => null,
				'task'      => null,
			],
			[
				'case'    => 'No view/task passed, no view/task in the input',
				'config'  => 'views.Cpanels.toolbar.main',
				'counter' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => null,
					'config'        => '',
				],
				'input'     => [],
				'useConfig' => false,
				'view'      => 'view',
				'task'      => null,
			],
			[
				'case'    => 'View passed, no view/task in the input',
				'config'  => 'views.Views.toolbar.main',
				'counter' => ['onViews' => 1],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => null,
					'config'        => '',
				],
				'input'     => [],
				'useConfig' => false,
				'view'      => 'foobar',
				'task'      => 'task',
			],
			[
				'case'    => 'View/task passed',
				'config'  => 'views.Foobars.toolbar.task',
				'counter' => ['onTask' => 1],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => null,
					'config'        => '',
				],
				'input'     => [],
				'useConfig' => false,
				'view'      => 'foobar',
				'task'      => 'dummy',
			],
			[
				'case'    => 'View/task passed',
				'config'  => 'views.Foobars.toolbar.dummy',
				'counter' => ['onFoobarsDummy' => 1],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => '',
					'getTask'       => '',
					'getController' => null,
					'config'        => '',
				],
				'input'     => [
					'view' => 'foobar',
					'task' => 'dummy',
				],
				'useConfig' => false,
				'view'      => null,
				'task'      => null,
			],
			[
				'case'    => 'No View/task passed, fetching them from the input',
				'config'  => 'views.Foobars.toolbar.dummy',
				'counter' => ['onFoobarsDummy' => 1],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => 'foobar',
					'getTask'       => 'dummy',
					'getController' => true,
					'config'        => '',
				],
				'input'     => [],
				'useConfig' => false,
				'view'      => null,
				'task'      => null,
			],
			[
				'case'    => 'No View/task passed, no view/task in the input, using controller default',
				'config'  => 'views.Foobars.toolbar.dummy',
				'counter' => ['onFoobarsDummy' => 1],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'getName'       => 'foobar',
					'getTask'       => 'dummy',
					'getController' => true,
					'config'        => [1],
				],
				'input'     => [],
				'useConfig' => true,
				'view'      => null,
				'task'      => null,
			],
			[
				'case'    => 'Using the configuration file',
				'config'  => 'views.Foobars.toolbar.dummy',
				'counter' => ['onFoobarsDummy' => 1],
			],
		];

		return $data;
	}

	public static function getTestOnCpanelsBrowse()
	{
		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'On backend, no dataview',
				'submenu' => true,
				'methods' => ['title' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'On backend, is a dataview',
				'submenu' => true,
				'methods' => ['title' => 1, 'preferences' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'No admin, no buttons',
				'submenu' => false,
				'methods' => [],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => true,
				'buttons' => false,
			],
			[
				'case'    => 'On frontend, with submenu',
				'submenu' => true,
				'methods' => [],
			],
		];

		return $data;
	}

	public static function getTestOnBrowse()
	{
		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [],
				'model'   => '',
			],
			[
				'case'    => 'On backend, no dataview',
				'submenu' => true,
				'methods' => ['title' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
				'model'   => '',
			],
			[
				'case'    => 'On backend, is a dataview, no model',
				'submenu' => true,
				'methods' => [
					'title'      => 1,
					'addNew'     => 1,
					'editList'   => 1,
					'divider'    => 1,
					'deleteList' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
				'model'   => 'specialfields',
			],
			[
				'case'    => 'On backend, is a dataview, model with special fields support',
				'submenu' => true,
				'methods' => [
					'title'         => 1,
					'addNew'        => 1,
					'editList'      => 1,
					'divider'       => 2,
					'publishList'   => 1,
					'unpublishList' => 1,
					'deleteList'    => 1,
					'checkin'       => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
				'model'   => 'bare',
			],
			[
				'case'    => 'On backend, is a dataview, model no special fields support',
				'submenu' => true,
				'methods' => [
					'title'      => 1,
					'addNew'     => 1,
					'editList'   => 1,
					'divider'    => 1,
					'deleteList' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [
					'manage'    => false,
					'create'    => false,
					'edit'      => false,
					'editstate' => false,
					'delete'    => false,
				],
				'model'   => '',
			],
			[
				'case'    => 'On backend, is a dataview, user can\'t do anything',
				'submenu' => true,
				'methods' => [
					'title' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [
					'manage'    => false,
					'create'    => false,
					'edit'      => false,
					'editstate' => false,
					'delete'    => false,
				],
				'model'   => 'specialfields',
			],
			[
				'case'    => 'On backend, is a dataview, model with special fields support, user can\'t do anything',
				'submenu' => true,
				'methods' => [
					'title' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
				'perms'   => [],
				'model'   => '',
			],
			[
				'case'    => 'No admin, no buttons',
				'submenu' => false,
				'methods' => [],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => true,
				'buttons' => false,
				'perms'   => [],
				'model'   => '',
			],
			[
				'case'    => 'On frontend, with submenu',
				'submenu' => true,
				'methods' => [],
			],
		];

		return $data;
	}

	public static function getTestOnRead()
	{
		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'On backend, no dataview',
				'submenu' => true,
				'methods' => ['title' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'On backend, is a dataview',
				'submenu' => true,
				'methods' => ['title' => 1, 'back' => 1],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => false,
				'buttons' => false,
			],
			[
				'case'    => 'No admin, no buttons',
				'submenu' => false,
				'methods' => [],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'submenu' => true,
				'buttons' => false,
			],
			[
				'case'    => 'On frontend, with submenu',
				'submenu' => true,
				'methods' => [],
			],
		];

		return $data;
	}

	public static function getTestOnAdd()
	{
		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'buttons' => false,
				'perms'   => [],
			],
			[
				'case'    => 'No admin, no buttons',
				'methods' => [],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => false,
					'dataView' => false,
				],
				'buttons' => true,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
			],
			[
				'case'    => 'No admin, with buttons',
				'methods' => [
					'title' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => false,
				],
				'buttons' => false,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
			],
			[
				'case'    => 'On backend, is not a dataview',
				'methods' => [
					'title' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'buttons' => false,
				'perms'   => [
					'manage'    => true,
					'create'    => true,
					'edit'      => true,
					'editstate' => true,
					'delete'    => true,
				],
			],
			[
				'case'    => 'On backend, is a dataview',
				'methods' => [
					'title'  => 1,
					'apply'  => 1,
					'save'   => 1,
					'custom' => 1,
					'cancel' => 1,
				],
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin'  => true,
					'dataView' => true,
				],
				'buttons' => false,
				'perms'   => [
					'manage'    => false,
					'create'    => false,
					'edit'      => false,
					'editstate' => false,
					'editown'   => false,
					'delete'    => false,
				],
			],
			[
				'case'    => 'On backend, is a dataview, user can\'t do anything',
				'methods' => [
					'title'  => 1,
					'save'   => 1,
					'cancel' => 1,
				],
			],
		];

		return $data;
	}

	public static function getTestOnEdit()
	{
		$data[] = [
			[
				'mock'    => [
					'isAdmin' => false,
				],
				'buttons' => false,
			],
			[
				'onAdd' => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin' => true,
				],
				'buttons' => false,
			],
			[
				'onAdd' => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'isAdmin' => false,
				],
				'buttons' => true,
			],
			[
				'onAdd' => true,
			],
		];

		return $data;
	}

	public static function getTestAppendLink()
	{
		$data[] = [
			[
				'mock'   => [
					'linkbar' => [],
				],
				'name'   => 'foobar',
				'link'   => null,
				'active' => false,
				'icon'   => null,
				'parent' => '',
			],
			[
				'case'    => 'No parent link, no links with the same name',
				'linkbar' => [
					'foobar' => ['name' => 'foobar', 'link' => null, 'active' => false, 'icon' => null],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'linkbar' => [
						'foobar' => ['name' => 'foobar', 'link' => null, 'active' => false, 'icon' => null],
					],
				],
				'name'   => 'foobar',
				'link'   => 'new_link',
				'active' => false,
				'icon'   => null,
				'parent' => '',
			],
			[
				'case'    => 'No parent link, link with the same name',
				'linkbar' => [
					'foobar' => ['name' => 'foobar', 'link' => 'new_link', 'active' => false, 'icon' => null],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'linkbar' => [
						'foobar' => [
							'name'  => 'foobar', 'link' => null, 'active' => false, 'icon' => null,
							'items' => ['some values'],
						],
					],
				],
				'name'   => 'foobar',
				'link'   => 'new_link',
				'active' => false,
				'icon'   => null,
				'parent' => '',
			],
			[
				'case'    => 'No parent link, link with the same name and with some children',
				'linkbar' => [
					'foobar' => [
						'name'   => 'foobar',
						'link'   => 'new_link',
						'active' => false,
						'icon'   => null,
						'items'  => [
							['name' => 'foobar', 'link' => 'new_link', 'active' => false, 'icon' => null],
							'some values',
						],
					],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'linkbar' => [],
				],
				'name'   => 'foobar',
				'link'   => null,
				'active' => true,
				'icon'   => null,
				'parent' => 'parent',
			],
			[
				'case'    => 'With parent link, no links with the same name',
				'linkbar' => [
					'parent' => [
						'name'     => 'parent',
						'link'     => null,
						'active'   => true,
						'icon'     => null,
						'items'    => [
							['name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null],
						],
						'dropdown' => 1,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'linkbar' => [
						'parent' => [
							'name'     => 'parent',
							'link'     => null,
							'active'   => true,
							'icon'     => null,
							'items'    => [
								['name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null],
							],
							'dropdown' => 1,
						],
					],
				],
				'name'   => 'dummy',
				'link'   => null,
				'active' => true,
				'icon'   => null,
				'parent' => 'parent',
			],
			[
				'case'    => 'With parent link, parent already exists',
				'linkbar' => [
					'parent' => [
						'name'     => 'parent',
						'link'     => null,
						'active'   => true,
						'icon'     => null,
						'items'    => [
							['name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null],
							['name' => 'dummy', 'link' => null, 'active' => true, 'icon' => null],
						],
						'dropdown' => 1,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'   => [
					'linkbar' => [
						'parent' => [
							'name'   => 'parent',
							'link'   => 'some link',
							'active' => true,
							'icon'   => null,
							'items'  => [
								['name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null],
							],
						],
					],
				],
				'name'   => 'dummy',
				'link'   => null,
				'active' => true,
				'icon'   => null,
				'parent' => 'parent',
			],
			[
				'case'    => 'With parent link, parent already exists with a link and no dropdown',
				'linkbar' => [
					'parent' => [
						'name'     => 'parent',
						'link'     => 'some link',
						'active'   => true,
						'icon'     => null,
						'items'    => [
							[
								'name'   => 'parent',
								'link'   => 'some link',
								'active' => true,
								'icon'   => null,
								'items'  => [
									['name' => 'foobar', 'link' => null, 'active' => true, 'icon' => null],
								],
							],
							['name' => 'dummy', 'link' => null, 'active' => true, 'icon' => null],
						],
						'dropdown' => 1,
					],
				],
			],
		];

		return $data;
	}

	public static function getTestRenderSubmenu()
	{
		$data[] = [
			[
				'input'   => [],
				'myviews' => [],
			],
			[
				'case'  => 'MyView returns empty',
				'links' => [],
			],
		];

		$data[] = [
			[
				'input'   => [
					'view' => 'items',
				],
				'myviews' => [
					'foobars',
					'items',
				],
			],
			[
				'case'  => 'We have some views',
				'links' => [
					['Foobars', 'index.php?option=com_fakeapp&view=foobars', null],
					['Items', 'index.php?option=com_fakeapp&view=items', true],
				],
			],
		];

		return $data;
	}

	public static function getTestIsDataView()
	{
		$data[] = [
			[
				'mock' => [
					'getController' => false,
					'getView'       => null,
					'cache'         => null,
				],
			],
			[
				'case'   => "The disptacher doesn't return a controller",
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getController' => true,
					'getView'       => null,
					'cache'         => null,
				],
			],
			[
				'case'   => "The controller doesn't return a view",
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getController' => true,
					'getView'       => 'FOF40\Tests\Stubs\View\ViewStub',
					'cache'         => null,
				],
			],
			[
				'case'   => "The view is not a data-aware one",
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getController' => true,
					'getView'       => 'FOF40\Tests\Stubs\View\DataView\RawStub',
					'cache'         => null,
				],
			],
			[
				'case'   => "The view is a data-aware one",
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'getController' => false,
					'getView'       => null,
					'cache'         => 'dummy',
				],
			],
			[
				'case'   => "Result is cached",
				'result' => 'dummy',
			],
		];

		return $data;
	}
}
