<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


class ViewDataprovider
{
	public static function getTest__construct()
	{
		$data[] = [
			[
				'config' => [],
				'mock'   => [
					'plugins' => [],
				],
			],
			[
				'case'           => 'Empty configuration, plugins do not add any path',
				'name'           => 'nestedset',
				'layout'         => 'default',
				'layoutTemplate' => '_',
				'templatePaths'  => [
					JPATH_THEMES . '/fake_test_template/html/com_fakeapp/nestedset/',
					JPATH_SITE . '/components/com_fakeapp/View/Nestedset/tmpl/',
				],
				'baseurl'        => 'www.example.com',
				'engines'        => [
					'.blade.php' => 'FOF40\View\Engine\BladeEngine',
					'.php'       => 'FOF40\View\Engine\PhpEngine',
				],
			],
		];

		$data[] = [
			[
				'config' => [
					'name'          => 'ConfigName',
					'template_path' => 'config/path',
					'layout'        => 'foo:bar',
					'viewEngineMap' => [
						'test' => 'testEngine',
					],
				],
				'mock'   => [
					'plugins' => [
						['plugin/path'],
					],
				],
			],
			[
				'case'           => 'Values in configuration, plugins do add some paths',
				'name'           => 'ConfigName',
				'layout'         => 'bar',
				'layoutTemplate' => 'foo',
				'templatePaths'  => [
					'plugin/path/',
					JPATH_THEMES . '/fake_test_template/html/com_fakeapp/ConfigName/',
					'config/path/',
				],
				'baseurl'        => 'www.example.com',
				'engines'        => [
					'.blade.php' => 'FOF40\View\Engine\BladeEngine',
					'.php'       => 'FOF40\View\Engine\PhpEngine',
					'test'       => 'testEngine',
				],
			],
		];

		$data[] = [
			[
				'config' => [
					'name'          => 'ConfigName',
					'template_path' => 'config/path',
					'layout'        => 'foo:bar',
					'viewEngineMap' => 'test => testEngine, test2 => test2Engine',
				],
				'mock'   => [
					'plugins' => [
						['plugin/path'],
					],
				],
			],
			[
				'case'           => 'Values in configuration (view engines are a string), plugins do add some paths',
				'name'           => 'ConfigName',
				'layout'         => 'bar',
				'layoutTemplate' => 'foo',
				'templatePaths'  => [
					'plugin/path/',
					JPATH_THEMES . '/fake_test_template/html/com_fakeapp/ConfigName/',
					'config/path/',
				],
				'baseurl'        => 'www.example.com',
				'engines'        => [
					'.blade.php' => 'FOF40\View\Engine\BladeEngine',
					'.php'       => 'FOF40\View\Engine\PhpEngine',
					'test'       => 'testEngine',
					'test2'      => 'test2Engine',
				],
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

	public function getTestGet()
	{
		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'foobars',
					'instances'    => [
						'foobars' => new \FOF40\Tests\Stubs\Model\ModelStub(
							new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_fakeapp',
							])
						),
					],
				],
				'property' => 'foobar',
				'default'  => null,
				'model'    => null,
			],
			[
				'case'   => 'Using default model, get<Property>() exists in the model',
				'result' => 'ok',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'foobars',
					'instances'    => [
						'foobars' => new \FOF40\Tests\Stubs\Model\ModelStub(
							new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_fakeapp',
							])
						),
					],
				],
				'property' => 'dummy',
				'default'  => null,
				'model'    => null,
			],
			[
				'case'   => 'Using default model, <Property>() exists in the model',
				'result' => 'ok',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'foobars',
					'instances'    => [
						'foobars' => new \FOF40\Tests\Stubs\Model\ModelStub(
							new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_fakeapp',
							])
						),
					],
				],
				'property' => 'nothere',
				'default'  => 'default',
				'model'    => null,
			],
			[
				'case'   => "Using default model, there isn't any method in the model",
				'result' => null,
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'dummy',
					'instances'    => [
						'foobars' => new \FOF40\Tests\Stubs\Model\ModelStub(
							new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_fakeapp',
							])
						),
					],
				],
				'property' => 'foobar',
				'default'  => null,
				'model'    => 'foobars',
			],
			[
				'case'   => 'Requesting for a specific model, get<Property>() exists in the model',
				'result' => 'ok',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'dummy',
					'instances'    => [
						'foobars' => new \FOF40\Tests\Stubs\Model\ModelStub(
							new \FOF40\Tests\Helpers\TestContainer([
								'componentName' => 'com_fakeapp',
							])
						),
					],
				],
				'property' => 'dummy',
				'default'  => null,
				'model'    => 'foobars',
			],
			[
				'case'   => 'Requesting for a specific model, <Property>() exists in the model',
				'result' => 'ok',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [
						'key'   => 'foobar',
						'value' => 'test',
					],
					'defaultModel' => 'foobars',
					'instances'    => [],
				],
				'property' => 'foobar',
				'default'  => 'default',
				'model'    => null,
			],
			[
				'case'   => 'Model not found, getting (existing) view property',
				'result' => 'test',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'viewProperty' => [],
					'defaultModel' => 'foobars',
					'instances'    => [],
				],
				'property' => 'foobar',
				'default'  => 'default',
				'model'    => null,
			],
			[
				'case'   => 'Model not found, getting (non-existing) view property',
				'result' => 'default',
			],
		];

		return $data;
	}

	public static function getTestGetModel()
	{
		$data[] = [
			[
				'name' => 'foobar',
				'mock' => [
					'name'         => null,
					'defaultModel' => null,
					'instances'    => [
						'foobar' => 'test',
					],
				],
			],
			[
				'case'      => 'Name passed',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'name' => null,
				'mock' => [
					'name'         => 'foobar',
					'defaultModel' => null,
					'instances'    => [
						'foobar' => 'test',
					],
				],
			],
			[
				'case'      => 'Using the view name',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'name' => null,
				'mock' => [
					'name'         => null,
					'defaultModel' => 'foobar',
					'instances'    => [
						'foobar' => 'test',
					],
				],
			],
			[
				'case'      => 'Using the default model name',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'name' => 'wrong',
				'mock' => [
					'name'         => null,
					'defaultModel' => null,
					'instances'    => [
						'foobar' => 'test',
					],
				],
			],
			[
				'case'      => 'Model not found',
				'result'    => '',
				'exception' => true,
			],
		];

		return $data;
	}

	public static function getTestDisplay()
	{
		// No template, everything is going smooth
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Foobar',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => null,
					'after'       => null,
					'output'      => 'test',
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, everything is going smooth',
				'output'    => 'test',
				'tpl'       => null,
				'exception' => false,
				'load'      => true,
				'before'    => ['counter' => 0],
				'after'     => ['counter' => 0],
			],
		];

		// With template, everything is going smooth
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Foobar',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => null,
					'after'       => null,
					'output'      => 'test',
				],
				'tpl'  => 'test',
			],
			[
				'case'      => 'With template, everything is going smooth',
				'output'    => 'test',
				'tpl'       => 'test',
				'exception' => false,
				'load'      => true,
				'before'    => ['counter' => 0],
				'after'     => ['counter' => 0],
			],
		];

		// With template, before/after methods are correctly called
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Dummy',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => true,
					'after'       => true,
					'output'      => 'test',
				],
				'tpl'  => 'test',
			],
			[
				'case'      => 'With template, before/after methods are correctly called',
				'output'    => 'test',
				'tpl'       => 'test',
				'exception' => false,
				'load'      => true,
				'before'    => ['counter' => 1],
				'after'     => ['counter' => 1],
			],
		];

		// No template, before throws an exception
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Dummy',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => false,
					'after'       => true,
					'output'      => 'test',
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, before throws an exception',
				'output'    => null,
				'tpl'       => null,
				'exception' => '\Exception',
				'load'      => false,
				'before'    => ['counter' => 1],
				'after'     => ['counter' => 0],
			],
		];

		// No template, after throws an exception
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Dummy',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => true,
					'after'       => false,
					'output'      => 'test',
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, after throws an exception',
				'output'    => null,
				'tpl'       => null,
				'exception' => '\Exception',
				'load'      => true,
				'before'    => ['counter' => 1],
				'after'     => ['counter' => 1],
			],
		];

		// No template, loadTemplate returns an exception
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Foobar',
					'doPreRender' => false,
					'pre'         => '',
					'before'      => null,
					'after'       => null,
					'output'      => new \Exception('', 500),
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, loadTemplate returns an exception',
				'output'    => null,
				'tpl'       => null,
				'exception' => '\Exception',
				'load'      => true,
				'before'    => ['counter' => 0],
				'after'     => ['counter' => 0],
			],
		];

		// doPreRender is false, preRender return something
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Foobar',
					'doPreRender' => false,
					'pre'         => 'pre-render',
					'before'      => null,
					'after'       => null,
					'output'      => 'test',
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, everything is going smooth',
				'output'    => 'test',
				'tpl'       => null,
				'exception' => false,
				'load'      => true,
				'before'    => ['counter' => 0],
				'after'     => ['counter' => 0],
			],
		];

		// doPreRender is true, preRender return something
		$data[] = [
			[
				'mock' => [
					'doTask'      => 'Foobar',
					'doPreRender' => true,
					'pre'         => 'pre-render ',
					'before'      => null,
					'after'       => null,
					'output'      => 'test',
				],
				'tpl'  => null,
			],
			[
				'case'      => 'No template, everything is going smooth',
				'output'    => 'pre-render test',
				'tpl'       => null,
				'exception' => false,
				'load'      => true,
				'before'    => ['counter' => 0],
				'after'     => ['counter' => 0],
			],
		];

		return $data;
	}

	public static function getTestLoadTemplate()
	{
		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => ['test'],
					'viewFinder' => ['first uri'],
				],
				'tpl'    => null,
				'strict' => false,
			],
			[
				'case'      => 'No template, no strict, we immediatly have a result',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => ['throw', 'throw', 'throw', 'throw', 'throw', 'throw'],
					'viewFinder' => ['first uri', 'second uri'],
				],
				'tpl'    => null,
				'strict' => false,
			],
			[
				'case'      => 'No template, no strict, we immediatly throw an exception',
				'result'    => new \Exception(),
				'exception' => true,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => [
						new \Exception(), new \Exception(), new \Exception(), new \Exception(), new \Exception(),
						new \Exception(),
					],
					'viewFinder' => ['first uri', 'second uri'],
				],
				'tpl'    => null,
				'strict' => false,
			],
			[
				'case'      => 'No template, no strict, we immediatly return an exception',
				'result'    => '',
				'exception' => true,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => ['throw', 'test'],
					'viewFinder' => ['first uri', 'second uri'],
				],
				'tpl'    => null,
				'strict' => false,
			],
			[
				'case'      => 'No template, no strict, we have a result after throwing some exceptions',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => [new \Exception(), 'test'],
					'viewFinder' => ['first uri', 'second uri'],
				],
				'tpl'    => null,
				'strict' => true,
			],
			[
				'case'      => 'No template, no strict, loadAny returns an exception',
				'result'    => 'test',
				'exception' => true,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => ['test'],
					'viewFinder' => ['first uri'],
				],
				'tpl'    => 'dummy',
				'strict' => false,
			],
			[
				'case'      => 'With template, no strict, we immediatly have a result',
				'result'    => 'test',
				'exception' => false,
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout'     => 'foobar',
					'any'        => ['test'],
					'viewFinder' => ['first uri'],
				],
				'tpl'    => 'dummy',
				'strict' => true,
			],
			[
				'case'      => 'With template and strict, we immediatly have a result',
				'result'    => 'test',
				'exception' => false,
			],
		];

		return $data;
	}

	public static function getTestLoadAnyTemplate()
	{
		global $fofTestConfig;

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => null,
				'mock'        => [
					'alias'         => [],
					'engineGet'     => [
						'type'    => 'raw',
						'content' => 'test',
					],
					'templatePaths' => '',
				],
			],
			[
				'case'   => 'No callback, no alias, raw engine',
				'result' => 'test',
				'uri'    => 'admin:com_fakeapp/foobar/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					$fofTestConfig['site_root'] . '/components/com_fakeapp/View/Nestedset/tmpl/',
				],
			],
		];

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => null,
				'mock'        => [
					'alias'         => [],
					'engineGet'     => [
						'type'    => 'raw',
						'content' => 'test',
					],
					'templatePaths' => 'extra/path',
				],
			],
			[
				'case'   => 'Using extra paths - 1',
				'result' => 'test',
				'uri'    => 'admin:com_fakeapp/foobar/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					'extra/path/',
				],
			],
		];

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => null,
				'mock'        => [
					'alias'         => [],
					'engineGet'     => [
						'type'    => 'raw',
						'content' => 'test',
					],
					'templatePaths' => 'extra/path2',
				],
			],
			[
				'case'   => 'Using extra paths - 2',
				'result' => 'test',
				'uri'    => 'admin:com_fakeapp/foobar/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					'extra/path2/',
				],
			],
		];

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => function ($view, $contents) {
					return 'callback';
				},
				'mock'        => [
					'alias'         => [
						'admin:com_fakeapp/foobar/default' => 'admin:com_fakeapp/alias/default',
					],
					'engineGet'     => [
						'type'    => 'raw',
						'content' => 'test',
					],
					'templatePaths' => '',
				],
			],
			[
				'case'   => 'Using URI alias and callback',
				'result' => 'callback',
				'uri'    => 'admin:com_fakeapp/alias/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					$fofTestConfig['site_root'] . '/components/com_fakeapp/View/Nestedset/tmpl/',
				],
			],
		];

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => null,
				'mock'        => [
					'alias'         => [],
					'engineGet'     => [
						'type'    => '.php',
						'content' => 'raw|test',
					],
					'templatePaths' => '',
				],
			],
			[
				'case'   => 'Using layout file with raw data',
				'result' => 'test',
				'uri'    => 'admin:com_fakeapp/foobar/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					$fofTestConfig['site_root'] . '/components/com_fakeapp/View/Nestedset/tmpl/',
				],
			],
		];

		$data[] = [
			[
				'uri'         => 'admin:com_fakeapp/foobar/default',
				'forceParams' => [],
				'callback'    => null,
				'mock'        => [
					'alias'         => [],
					'engineGet'     => [
						'type'    => '.php',
						'content' => JPATH_TESTS . '/Stubs/Fakeapp/Admin/View/Foobar/tmpl/default.php',
					],
					'templatePaths' => '',
				],
			],
			[
				'case'   => 'Actually including a layout file',
				'result' => 'Layout text',
				'uri'    => 'admin:com_fakeapp/foobar/default',
				'extra'  => [
					$fofTestConfig['site_root'] . '/templates/fake_test_template/html/com_fakeapp/nestedset/',
					$fofTestConfig['site_root'] . '/components/com_fakeapp/View/Nestedset/tmpl/',
				],
			],
		];

		return $data;
	}

	public static function getTestFlushSectionsIfDoneRendering()
	{
		$data[] = [
			[
				'mock' => [
					'done' => false,
				],
			],
			[
				'flush' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'done' => true,
				],
			],
			[
				'flush' => true,
			],
		];

		return $data;
	}

	public static function getTestDoneRendering()
	{
		$data[] = [
			[
				'counter' => 0,
			],
			[
				'case'   => 'Internal counter is 0',
				'result' => true,
			],
		];

		$data[] = [
			[
				'counter' => 10,
			],
			[
				'case'   => 'Internal counter is not 0',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestRenderEach()
	{
		$data[] = [
			[
				'data'  => [1, 2],
				'empty' => 'raw|',
				'mock'  => [
					'loadAny' => ['first ', 'second'],
				],
			],
			[
				'case'    => 'Data not empty',
				'result'  => 'first second',
				'loadAny' => true,
			],
		];

		$data[] = [
			[
				'data'  => [],
				'empty' => 'raw|default data',
				'mock'  => [
					'loadAny' => ['first ', 'second'],
				],
			],
			[
				'case'    => 'Empty data',
				'result'  => 'default data',
				'loadAny' => false,
			],
		];

		$data[] = [
			[
				'data'  => [],
				'empty' => 'admin:com_fakeapp/foobar/empty',
				'mock'  => [
					'loadAny' => ['default data from layout'],
				],
			],
			[
				'case'    => 'Empty data, loading a layout',
				'result'  => 'default data from layout',
				'loadAny' => true,
			],
		];

		return $data;
	}

	public static function getTestSetLayout()
	{
		$data[] = [
			[
				'mock'   => [
					'layout' => null,
				],
				'layout' => 'foobar',
			],
			[
				'case'   => 'Internal layout is null, passing simple layout',
				'result' => null,
				'layout' => 'foobar',
				'tmpl'   => '_',
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout' => 'previous',
				],
				'layout' => 'foobar',
			],
			[
				'case'   => 'Internal layout is set, passing simple layout',
				'result' => 'previous',
				'layout' => 'foobar',
				'tmpl'   => '_',
			],
		];

		$data[] = [
			[
				'mock'   => [
					'layout' => null,
				],
				'layout' => 'foo:bar',
			],
			[
				'case'   => 'Internal layout is null, passing layout + template',
				'result' => null,
				'layout' => 'bar',
				'tmpl'   => 'foo',
			],
		];

		return $data;
	}

	public static function getTestStartSection()
	{
		$data[] = [
			[
				'section' => 'foobar',
				'content' => '',
				'mock'    => [
					'sections' => [],
				],
			],
			[
				'case'        => 'Starting a new section with empty contents',
				'stack'       => ['foobar'],
				'sections'    => [],
				'closeBuffer' => true,
			],
		];

		$data[] = [
			[
				'section' => 'foobar',
				'content' => 'dummy content',
				'mock'    => [
					'sections' => [],
				],
			],
			[
				'case'        => "Adding contents to a section and it's not in the sections array",
				'stack'       => [],
				'sections'    => [
					'foobar' => 'dummy content',
				],
				'closeBuffer' => false,
			],
		];

		$data[] = [
			[
				'section' => 'foobar',
				'content' => 'dummy content',
				'mock'    => [
					'sections' => [
						'foobar' => 'old content',
					],
				],
			],
			[
				'case'        => "Adding contents to a section that's in the sections array",
				'stack'       => [],
				'sections'    => [
					'foobar' => 'old content',
				],
				'closeBuffer' => false,
			],
		];

		$data[] = [
			[
				'section' => 'foobar',
				'content' => 'dummy content',
				'mock'    => [
					'sections' => [
						'foobar' => '@parent old content',
					],
				],
			],
			[
				'case'        => "Adding contents to a section that's in the sections array, using the @parent keyword",
				'stack'       => [],
				'sections'    => [
					'foobar' => 'dummy content old content',
				],
				'closeBuffer' => false,
			],
		];

		return $data;
	}

	public static function getTestStopSection()
	{
		$data[] = [
			[
				'overwrite' => false,
				'contents'  => 'test content',
				'mock'      => [
					'stack'    => ['foobar'],
					'sections' => [
						'foobar' => 'old content',
					],
				],
			],
			[
				'case'     => 'Not overwriting the section, current section has no @parent tag',
				'result'   => 'foobar',
				'contents' => 'old content',
			],
		];

		$data[] = [
			[
				'overwrite' => false,
				'contents'  => 'test content',
				'mock'      => [
					'stack'    => ['foobar'],
					'sections' => [
						'foobar' => 'old content @parent',
					],
				],
			],
			[
				'case'     => 'Not overwriting the section, current section has the @parent tag',
				'result'   => 'foobar',
				'contents' => 'old content test content',
			],
		];

		$data[] = [
			[
				'overwrite' => true,
				'contents'  => 'test content',
				'mock'      => [
					'stack'    => ['foobar'],
					'sections' => [
						'foobar' => 'old content',
					],
				],
			],
			[
				'case'     => 'Overwriting the section',
				'result'   => 'foobar',
				'contents' => 'test content',
			],
		];

		return $data;
	}

	public static function getTestAppendSection()
	{
		$data[] = [
			[
				'contents' => 'test content',
				'mock'     => [
					'stack'    => ['foobar'],
					'sections' => [],
				],
			],
			[
				'case'     => 'Section already does not exist',
				'result'   => 'foobar',
				'contents' => 'test content',
			],
		];

		$data[] = [
			[
				'contents' => ' test content',
				'mock'     => [
					'stack'    => ['foobar'],
					'sections' => [
						'foobar' => 'old content',
					],
				],
			],
			[
				'case'     => 'Section already exists',
				'result'   => 'foobar',
				'contents' => 'old content test content',
			],
		];

		return $data;
	}

	public static function getTestYieldContent()
	{
		$data[] = [
			[
				'section' => 'wrong',
				'default' => '@parent Lorem ipsum',
				'mock'    => [
					'sections' => [],
				],
			],
			[
				'case'   => 'Section not set, using the default',
				'result' => ' Lorem ipsum',
			],
		];

		$data[] = [
			[
				'section' => 'present',
				'default' => '@parent Lorem ipsum',
				'mock'    => [
					'sections' => [
						'present' => '@parent Found',
					],
				],
			],
			[
				'case'   => 'Section set',
				'result' => ' Found',
			],
		];

		return $data;
	}
}
