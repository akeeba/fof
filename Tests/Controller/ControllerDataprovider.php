<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class ControllerDataprovider
{
	public static function getTest__construct()
	{
		$data[] = [
			[
				'layout' => null,
				'config' => [],
			],
			[
				'case'        => 'No layout, no config passed',
				'defaultTask' => 'main',
				'layout'      => null,
				'viewName'    => false,
				'modelName'   => false,
				'name'        => 'dummycontroller',
				'autoroute'   => 0,
				'csrf'        => 2,
			],
		];

		$data[] = [
			[
				'layout' => 'foobar',
				'config' => [
					'viewName'       => 'dummy',
					'modelName'      => 'dummy',
					'default_view'   => 'foobar',
					'default_task'   => 'dummy',
					'name'           => 'dummycontroller',
					'autoRouting'    => 1,
					'csrfProtection' => 1,
				],
			],
			[
				'case'        => 'Layout and config passed',
				'defaultTask' => 'dummy',
				'layout'      => 'foobar',
				'viewName'    => 'dummy',
				'modelName'   => 'dummy',
				'name'        => 'dummycontroller',
				'autoroute'   => 1,
				'csrf'        => 1,
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
				'case'   => 'Requesting a non-existing method',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestExecute()
	{
		$data[] = [
			[
				'task' => 'foobar',
				'mock' => [
					'before'  => true,
					'task'    => true,
					'after'   => true,
					'taskMap' => [
						'foobar'    => 'foobar',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array',
				'doTask' => 'foobar',
				'before' => 0,
				'task'   => 1,
				'after'  => 0,
				'result' => true,
			],
		];

		$data[] = [
			[
				'task' => 'foobar',
				'mock' => [
					'before'  => true,
					'task'    => false,
					'after'   => true,
					'taskMap' => [
						'foobar'    => 'foobar',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array',
				'doTask' => 'foobar',
				'before' => 0,
				'task'   => 1,
				'after'  => 0,
				'result' => false,
			],
		];

		$data[] = [
			[
				'task' => 'foobar',
				'mock' => [
					'before'  => true,
					'task'    => true,
					'after'   => true,
					'taskMap' => [
						'__default' => 'foobar',
					],
				],
			],
			[
				'case'   => 'Task is defined as default inside the taskMap array',
				'doTask' => 'foobar',
				'before' => 0,
				'task'   => 1,
				'after'  => 0,
				'result' => true,
			],
		];

		$data[] = [
			[
				'task' => 'dummy',
				'mock' => [
					'before'  => true,
					'task'    => true,
					'after'   => true,
					'taskMap' => [
						'dummy'     => 'dummy',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array, onBefore and onAfter return true',
				'doTask' => 'dummy',
				'before' => 1,
				'task'   => 1,
				'after'  => 1,
				'result' => true,
			],
		];

		$data[] = [
			[
				'task' => 'dummy',
				'mock' => [
					'before'  => false,
					'task'    => true,
					'after'   => true,
					'taskMap' => [
						'dummy'     => 'dummy',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array, onBefore returns false and onAfter returns true',
				'doTask' => null,
				'before' => 1,
				'task'   => 0,
				'after'  => 0,
				'result' => false,
			],
		];

		$data[] = [
			[
				'task' => 'dummy',
				'mock' => [
					'before'  => true,
					'task'    => true,
					'after'   => false,
					'taskMap' => [
						'dummy'     => 'dummy',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array, onBefore returns true and onAfter returns false',
				'doTask' => 'dummy',
				'before' => 1,
				'task'   => 1,
				'after'  => 1,
				'result' => false,
			],
		];

		$data[] = [
			[
				'task' => 'dummy',
				'mock' => [
					'before'  => true,
					'task'    => false,
					'after'   => false,
					'taskMap' => [
						'dummy'     => 'dummy',
						'__default' => 'test',
					],
				],
			],
			[
				'case'   => 'Task is defined inside the taskMap array, task returns false onBefore returns true and onAfter returns false',
				'doTask' => 'dummy',
				'before' => 1,
				'task'   => 1,
				'after'  => 1,
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestDisplay()
	{
		$data[] = [
			[
				'mock' => [
					'getModel' => false,
					'task'     => 'foobar',
					'doTask'   => 'foobar',
					'layout'   => null,
				],
			],
			[
				'case'          => 'Model not created, layout is null',
				'modelCounter'  => 0,
				'layoutCounter' => 0,
				'layout'        => null,
				'task'          => 'foobar',
				'doTask'        => 'foobar',
			],
		];

		$data[] = [
			[
				'mock' => [
					'getModel' => new \FOF40\Tests\Stubs\Model\ModelStub(
						new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_eastwood',
							]
						)
					),
					'task'     => 'foobar',
					'doTask'   => 'foobar',
					'layout'   => 'dummy',
				],
			],
			[
				'case'          => 'Model created, layout is not null',
				'modelCounter'  => 1,
				'layoutCounter' => 1,
				'layout'        => 'dummy',
				'task'          => 'foobar',
				'doTask'        => 'foobar',
			],
		];

		return $data;
	}

	public static function getTestGetModel()
	{
		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'modelName' => null,
					'instances' => [],
					'getModel'  => true,
				],
			],
			[
				'case'      => 'Name passed, model not cached, internal reference are empty',
				'result'    => true,
				'modelName' => 'Foobar',
				'config'    => [
					'modelTemporaryInstance' => false,
					'modelClearState'        => true,
					'modelClearInput'        => true,
				],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => ['foo' => 'bar'],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'modelName' => null,
					'instances' => [],
					'getModel'  => 'mocked',
				],
			],
			[
				'case'      => 'Name and config passed, model not cached, internal reference are empty',
				'result'    => 'mocked',
				'modelName' => 'Foobar',
				'config'    => [
					'foo'                    => 'bar',
					'modelTemporaryInstance' => false,
					'modelClearState'        => true,
					'modelClearInput'        => true,
				],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [
					'modelConfig' => [
						'foo' => 'bar',
					],
				],
				'mock'            => [
					'view'      => null,
					'modelName' => null,
					'instances' => [],
					'getModel'  => 'mocked',
				],
			],
			[
				'case'      => 'Name and config passed (in the constructor), model not cached, internal reference are empty',
				'result'    => 'mocked',
				'modelName' => 'Foobar',
				'config'    => [
					'foo'                    => 'bar',
					'modelTemporaryInstance' => false,
					'modelClearState'        => true,
					'modelClearInput'        => true,
				],
			],
		];

		$data[] = [
			[
				'name'            => null,
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'modelName' => 'foobar',
					'instances' => [],
					'getModel'  => true,
				],
			],
			[
				'case'      => 'Name not passed, model not cached, using modelName property',
				'result'    => true,
				'modelName' => 'Foobar',
				'config'    => [
					'modelTemporaryInstance' => true,
					'hash_view'              => 'dummycontroller',
				],
			],
		];

		$data[] = [
			[
				'name'            => null,
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => 'foobar',
					'modelName' => null,
					'instances' => [],
					'getModel'  => 'mocked',
				],
			],
			[
				'case'      => 'Name not passed, model not cached, using view property',
				'result'    => 'mocked',
				'modelName' => 'Foobar',
				'config'    => [
					'modelTemporaryInstance' => true,
					'hash_view'              => 'dummycontroller',
				],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'modelName' => null,
					'instances' => ['foobar' => 'cached'],
					'getModel'  => true,
				],
			],
			[
				'case'      => 'Name passed, fetching the model from the cache',
				'result'    => 'cached',
				'modelName' => '',
				'config'    => [],
			],
		];

		return $data;
	}

	public static function getTestGetView()
	{
		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => [],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name passed, view not cached, internal reference are empty',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => [],
					'format'    => 'html',
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name passed, view not cached, internal reference are empty',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => null,
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => 'foobar',
					'instances' => [],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name not passed, fetched from the viewName property',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => null,
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => 'foobar',
					'viewName'  => null,
					'instances' => [],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name not passed, fetched from the view property',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => [],
					'format'    => 'json',
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating JSON view, name passed, view not cached, internal reference are empty',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'json',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => ['foobar' => 'cached'],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, fetched from the cache',
				'result'   => 'cached',
				'viewName' => '',
				'type'     => '',
				'config'   => [],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => ['foo' => 'bar'],
				'constructConfig' => [],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => [],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name and config passed, view not cached, internal reference are empty',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => ['foo' => 'bar'],
			],
		];

		$data[] = [
			[
				'name'            => 'foobar',
				'config'          => [],
				'constructConfig' => [
					'viewConfig' => [
						'foo' => 'bar',
					],
				],
				'mock'            => [
					'view'      => null,
					'viewName'  => null,
					'instances' => [],
					'format'    => null,
					'getView'   => 'mocked',
				],
			],
			[
				'case'     => 'Creating HTML view, name and config passed (in constructor), view not cached, internal reference are empty',
				'result'   => 'mocked',
				'viewName' => 'foobar',
				'type'     => 'html',
				'config'   => ['foo' => 'bar'],
			],
		];

		return $data;
	}

	public static function getTestRedirect()
	{
		$data[] = [
			[
				'mock' => [
					'redirect' => 'index.php',
				],
			],
			[
				'case'     => 'A redirect as been set',
				'result'   => null,
				'redirect' => 1,
			],
		];

		$data[] = [
			[
				'mock' => [
					'redirect' => null,
				],
			],
			[
				'case'     => 'No redirection set',
				'result'   => false,
				'redirect' => 0,
			],
		];

		return $data;
	}

	public static function getTestRegisterTask()
	{
		$data[] = [
			[
				'task'   => 'dummy',
				'method' => 'foobar',
				'mock'   => [
					'methods' => ['foobar'],
				],
			],
			[
				'case'     => 'Method is mapped inside the controller',
				'register' => true,
			],
		];

		$data[] = [
			[
				'task'   => 'dummy',
				'method' => 'foobar',
				'mock'   => [
					'methods' => [],
				],
			],
			[
				'case'     => 'Method is not mapped inside the controller',
				'register' => false,
			],
		];

		return $data;
	}

	public static function getTestSetMessage()
	{
		$data[] = [
			[
				'message' => 'foo',
				'type'    => null,
				'mock'    => [
					'previous' => 'bar',
				],
			],
			[
				'case'    => '$type argument is null',
				'result'  => 'bar',
				'message' => 'foo',
				'type'    => 'message',
			],
		];

		$data[] = [
			[
				'message' => 'foo',
				'type'    => 'warning',
				'mock'    => [
					'previous' => 'bar',
				],
			],
			[
				'case'    => 'Message type is defined',
				'result'  => 'bar',
				'message' => 'foo',
				'type'    => 'warning',
			],
		];

		return $data;
	}

	public static function getTestSetRedirect()
	{
		$data[] = [
			[
				'url'  => 'index.php',
				'msg'  => null,
				'type' => null,
				'mock' => [
					'type' => null,
				],
			],
			[
				'case'     => 'Url is set, message and type are null; controller messageType is null',
				'redirect' => 'index.php',
				'message'  => null,
				'type'     => 'message',
			],
		];

		$data[] = [
			[
				'url'  => 'index.php',
				'msg'  => null,
				'type' => null,
				'mock' => [
					'type' => 'warning',
				],
			],
			[
				'case'     => 'Url is set, message and type are null; controller messageType is not null',
				'redirect' => 'index.php',
				'message'  => null,
				'type'     => 'warning',
			],
		];

		$data[] = [
			[
				'url'  => 'index.php',
				'msg'  => null,
				'type' => 'info',
				'mock' => [
					'type' => 'warning',
				],
			],
			[
				'case'     => 'Url and type are set, message is null; controller messageType is not null',
				'redirect' => 'index.php',
				'message'  => null,
				'type'     => 'info',
			],
		];

		$data[] = [
			[
				'url'  => 'index.php',
				'msg'  => 'Foobar',
				'type' => 'info',
				'mock' => [
					'type' => 'warning',
				],
			],
			[
				'case'     => 'Url, type and message are set, controller messageType is not null',
				'redirect' => 'index.php',
				'message'  => 'Foobar',
				'type'     => 'info',
			],
		];

		return $data;
	}
}
