<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Tests\View;

/**
 * Data provider for the ViewTemplateFinderTest class
 */
abstract class ViewTemplateFinderProvider
{
	public static function constructProvider()
	{
		return [
			'Typical' => [
				// config, expected
				[
					'extensions'    => '.foo, .bar',
					'defaultLayout' => 'baz',
					'defaultTpl'    => 'bat',
					'strictView'    => 'on',
					'strictTpl'     => 'true',
					'strictLayout'  => 'yes',
					'sidePrefix'    => 'auto',
				],
				[
					'extensions'    => ['.foo', '.bar'],
					'defaultLayout' => 'baz',
					'defaultTpl'    => 'bat',
					'strictView'    => true,
					'strictTpl'     => true,
					'strictLayout'  => true,
					'sidePrefix'    => 'auto',

				],
			],

			'Defaults' => [
				// config, expected
				[
				],
				[
					'extensions'    => ['.blade.php', '.php'],
					'defaultLayout' => 'default',
					'defaultTpl'    => '',
					'strictView'    => true,
					'strictTpl'     => true,
					'strictLayout'  => true,
					'sidePrefix'    => 'auto',

				],
			],

		];
	}

	public static function setSidePrefixProvider()
	{
		return [
			'Site'                  => ['site', 'site'],
			'Admin'                 => ['admin', 'admin'],
			'Auto'                  => ['auto', 'auto'],
			'Any'                   => ['any', 'any'],
			'Invalid (map to auto)' => ['foobar', 'auto'],
		];
	}

	public static function addExtensionProvider()
	{
		return [
			// $add, $expected
			'Nothing to add (empty string)' => ['', ['.foo']],
			'No dot'                        => ['bar', ['.foo', '.bar']],
			'With dot'                      => ['.bar', ['.foo', '.bar']],
			'Already exists (no dot)'       => ['foo', ['.foo']],
			'Already exists (with dot)'     => ['.foo', ['.foo']],
		];
	}

	public static function removeExtensionProvider()
	{
		return [
			'Nothing to remove (empty string)'      => ['', ['.foo', '.bar']],
			'Nothing to remove (invalid, no dot)'   => ['baz', ['.foo', '.bar']],
			'Nothing to remove (invalid, with dot)' => ['.baz', ['.foo', '.bar']],
			'With dot'                              => ['.bar', ['.foo']],
			'No dot'                                => ['bar', ['.foo']],
		];
	}

	public static function parseTemplateUriProvider()
	{
		return [
			'Admin, full definition'                => [
				// $uri, $expected
				'admin:com_foobar/baz/bat',
				[
					'admin'     => 1,
					'component' => 'com_foobar',
					'view'      => 'baz',
					'template'  => 'bat',
				],
			],
			'Site, full definition'                 => [
				// $uri, $expected
				'site:com_foobar/baz/bat',
				[
					'admin'     => 0,
					'component' => 'com_foobar',
					'view'      => 'baz',
					'template'  => 'bat',
				],
			],
			'Any, full definition'                  => [
				// $uri, $expected
				'any:com_foobar/baz/bat',
				[
					'admin'     => -1,
					'component' => 'com_foobar',
					'view'      => 'baz',
					'template'  => 'bat',
				],
			],
			'Admin, no component, view or template' => [
				// $uri, $expected
				'admin:',
				[
					'admin'     => 1,
					'component' => 'com_fakeapp',
					'view'      => 'foobar',
					'template'  => 'default',
				],
			],
			'Admin, no view or template'            => [
				// $uri, $expected
				'admin:com_test',
				[
					'admin'     => 1,
					'component' => 'com_test',
					'view'      => 'foobar',
					'template'  => 'default',
				],
			],
			'Admin, no template'                    => [
				// $uri, $expected
				'admin:com_test/bat',
				[
					'admin'     => 1,
					'component' => 'com_test',
					'view'      => 'bat',
					'template'  => 'default',
				],
			],
		];
	}

	public static function getViewTemplateUrisProvider()
	{
		return [
			'Defaults'                               => [
				[],
				[
					'auto:com_fakeapp/foobar/default',
				],
			],
			'Non-strict tpl'                         => [
				[
					'strictTpl' => false,
					'tpl'       => 'test',
				],
				[
					'auto:com_fakeapp/foobar/default_test',
					'auto:com_fakeapp/foobar/default',
				],
			],
			'Non-strict layout'                      => [
				[
					'strictLayout' => false,
					'layout'       => 'baz',
				],
				[
					'auto:com_fakeapp/foobar/baz',
					'auto:com_fakeapp/foobar/default',
				],
			],
			'Non-strict view (original is singular)' => [
				[
					'strictView' => false,
					'view'       => 'item',
				],
				[
					'auto:com_fakeapp/item/default',
					'auto:com_fakeapp/items/default',
				],
			],
			'Non-strict view (original is plural)'   => [
				[
					'strictView' => false,
					'view'       => 'items',
				],
				[
					'auto:com_fakeapp/items/default',
					'auto:com_fakeapp/item/default',
				],
			],
			'Non-strict anything' => [
				[
					'view'          => 'item',
					'layout'        => 'baz',
					'tpl'           => 'test',
					'strictView'    => false,
					'strictLayout'  => false,
					'strictTpl'     => false,
					'sidePrefix'    => 'auto',
				],
				[
					'auto:com_fakeapp/item/baz_test',
					'auto:com_fakeapp/item/baz',
					'auto:com_fakeapp/item/default_test',
					'auto:com_fakeapp/item/default',
					'auto:com_fakeapp/items/baz_test',
					'auto:com_fakeapp/items/baz',
					'auto:com_fakeapp/items/default_test',
					'auto:com_fakeapp/items/default',
				]
			],
		];
	}

	public static function resolveUriToPathProvider()
	{
		$virtualFiles = [
			'admin/components/com_foobar/ViewTemplates/auto/default.php',
			'site/components/com_foobar/views/one/tmpl/default.php',
			'site/components/com_foobar/View/two/tmpl/default.php',
			'site/components/com_foobar/View/priority/tmpl/default.php',
			'site/components/com_foobar/View/priority/tmpl/vampire.blade.php',
			'site/components/com_foobar/View/priority/tmpl/override.php',
			'site/components/com_foobar/View/priority/tmpl/nooverride.blade.php',
			'site/components/com_foobar/View/priority/tmpl/with_tpl.php',
			'site/components/com_foobar/View/priority/tmpl/with.blade.php',
			'site/components/com_foobar/ViewTemplates/three/default.php',
			'site/components/com_foobar/ViewTemplates/priority/default.php',
			'site/components/com_foobar/ViewTemplates/priority/wesley.blade.php',
			'site/components/com_foobar/ViewTemplates/priority/wesley.php',
			'site/components/com_foobar/ViewTemplates/priority/vampire.php',
			'site/components/com_foobar/ViewTemplates/priority/override.php',
			'site/components/com_foobar/ViewTemplates/priority/nooverride.php',
			'site/components/com_foobar/ViewTemplates/auto/default.php',
			'site/components/com_foobar/ViewTemplates/auto/foobar.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.j99.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.j99.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.j9.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.j9.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor2.j99.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor2.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor3.j99.php',
			'site/components/com_foobar/ViewTemplates/suffixes/majorminor3.j9.php',
			'site/components/com_foobar/ViewTemplates/suffixes/major.j9.php',
			'site/components/com_foobar/ViewTemplates/suffixes/major.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/withblade.j99.joomla.php',
			'site/components/com_foobar/ViewTemplates/suffixes/withblade.blade.php',
			'template/html/com_foobar/priority/override.php',
			'template/html/com_foobar/priority/nooverride.php',
		];

		return [
			'Typical, using legacy views folder (no longer supported)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/one/default', false,
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Typical, using View folder' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/two/default', 'vfs://siteroot/site/components/com_foobar/View/two/tmpl/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Typical, using ViewTemplates folder' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/three/default', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/three/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure ViewTemplates has priority over View' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/default', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/priority/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure .blade.php has priority over .php' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/wesley', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/priority/wesley.blade.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure .blade.php has priority over .php even if .php is in ViewTemplates' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/vampire', 'vfs://siteroot/site/components/com_foobar/View/priority/tmpl/vampire.blade.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure .blade.php has priority over .php even if .php is in a template override' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/nooverride', 'vfs://siteroot/site/components/com_foobar/View/priority/tmpl/nooverride.blade.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure template override has priority over View and ViewTemplate' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/override', 'vfs://siteroot/template/html/com_foobar/priority/override.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Ensure tpl is respected and overrides extension order' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/priority/with_tpl', 'vfs://siteroot/site/components/com_foobar/View/priority/tmpl/with_tpl.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'auto: needs to pick the correct side (frontend)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'auto:com_foobar/auto/default', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/auto/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'auto: needs to pick the correct side (backend)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'auto:com_foobar/auto/default', 'vfs://siteroot/admin/components/com_foobar/ViewTemplates/auto/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				true, '', [], []
			],
			'any: needs to prioritize current side (frontend)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'any:com_foobar/auto/default', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/auto/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'any: needs to prioritize current side (backend)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'auto:com_foobar/auto/default', 'vfs://siteroot/admin/components/com_foobar/ViewTemplates/auto/default.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				true, '', [], []
			],
			'any: needs to pick either side (backend w/ view in frontend)' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'any:com_foobar/auto/foobar', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/auto/foobar.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				true, '', [], []
			],
			'Suffixes: Major/Minor + renderer is most specific given all options' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/suffixes/majorminor', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/suffixes/majorminor.j99.joomla.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Suffixes: Major/Minor is more specific than renderer only' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/suffixes/majorminor2', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/suffixes/majorminor2.j99.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Suffixes: Major/Minor is more specific than Major only' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/suffixes/majorminor3', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/suffixes/majorminor3.j99.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Suffixes: Major only is more specific than renderer only' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/suffixes/major', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/suffixes/major.j9.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			],
			'Suffixes, extension order matters: Bare .blade.php is more specific than Major/minor + renderer .php' => [
				// $virtualFiles, $uri, $expected
				$virtualFiles, 'site:com_foobar/suffixes/withblade', 'vfs://siteroot/site/components/com_foobar/ViewTemplates/suffixes/withblade.blade.php',
				// $backend, $layoutTemplate, $extraPaths, $extraConfig
				false, '', [], []
			]
			// 'Common templates: using the Joomla! renderer results in common template NOT being used'
			// 'Common templates: using the FEF renderer results in common template being used'
			// 'Common templates: component .blade.php overrides Common Template'
			// 'Common templates: component .php overrides Common Template'
			// 'Common templates: component-level template override .blade.php overrides Common Template'
			// 'Common templates: component-level template override .php overrides Common Template'
			// 'Common templates: library-level template override .blade.php overrides Common Template'
			// 'Common templates: library-level template override .php DOES NOT override Common Template'
		];
	}

}
