<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Platform;


class PlatformJoomlaProvider
{
	public static function getTestIsCli()
	{
		// $mockApplicationType, $expected, $message
		return [
			['site', false, 'Site app is not a CLI app'],
			['admin', false, 'Admin app is not a CLI app'],
			['cli', true, 'Cli app is a CLI app'],
			['exception', true, 'Exception instead of app is a CLI app'],
		];
	}

	public static function getTestIsBackend()
	{
		// $mockApplicationType, $expected, $message
		return [
			['site', false, 'Site app is not a backend app'],
			['admin', true, 'Admin app is not a backend app'],
			['cli', false, 'Cli app is not a backend app'],
			['exception', false, 'Exception instead of app is not a backend app'],
		];
	}

	public static function getTestIsFrontend()
	{
		// $mockApplicationType, $expected, $message
		return [
			['site', true, 'Site app is a frontend app'],
			['admin', false, 'Admin app is not a Frontend app'],
			['cli', false, 'Cli app is not a Frontend app'],
			['exception', false, 'Exception instead of app is not a Frontend app'],
		];
	}

	public static function getTestComponentBaseDirs()
	{
		// $area, $expectedMain, $expectedAlt
		return [
			['site', 'components/com_foobar', 'administrator/components/com_foobar'],
			['cli', 'components/com_foobar', 'administrator/components/com_foobar'],
			['exception', 'components/com_foobar', 'administrator/components/com_foobar'],
			['admin', 'administrator/components/com_foobar', 'components/com_foobar'],
		];
	}

	public static function getTestGetViewTemplatePaths()
	{
		// $area, $view, $layout, $tpl, $strict, $expected, $message
		return [
			[
				'site', 'item', null, null, false, [
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			], 'Site app, singular view, no layout, no tpl, no strict',
			],

			[
				'site', 'item', null, null, true, [
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			], 'Site app, singular view, no layout, no tpl, strict',
			],

			[
				'site', 'item', null, 'bar', false, [
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			], 'Site app, singular view, no layout, tpl bar, no strict',
			],

			[
				'site', 'item', null, 'bar', true, [
				'site:com_foobar/item/default_bar',
				'site:com_foobar/items/default_bar',
			], 'Site app, singular view, no layout, tpl bar, strict',
			],

			[
				'site', 'item', 'foo', null, false, [
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
			], 'Site app, singular view, foo layout, no tpl, no strict',
			],

			[
				'site', 'item', 'foo', null, true, [
				'site:com_foobar/item/foo',
				'site:com_foobar/items/foo',
			], 'Site app, singular view, no layout, no tpl, strict',
			],

			[
				'site', 'item', 'foo', 'bar', false, [
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			], 'Site app, singular view, no layout, tpl bar, no strict',
			],

			[
				'site', 'item', 'foo', 'bar', true, [
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/items/foo_bar',
			], 'Site app, singular view, no layout, tpl bar, strict',
			],

			// ------

			[
				'site', 'items', null, null, false, [
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			], 'Site app, plural view, no layout, no tpl, no strict',
			],

			[
				'site', 'items', null, null, true, [
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			], 'Site app, plural view, no layout, no tpl, strict',
			],

			[
				'site', 'items', null, 'bar', false, [
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			], 'Site app, plural view, no layout, tpl bar, no strict',
			],

			[
				'site', 'items', null, 'bar', true, [
				'site:com_foobar/items/default_bar',
				'site:com_foobar/item/default_bar',
			], 'Site app, plural view, no layout, tpl bar, strict',
			],

			[
				'site', 'items', 'foo', null, false, [
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
			], 'Site app, plural view, foo layout, no tpl, no strict',
			],

			[
				'site', 'items', 'foo', null, true, [
				'site:com_foobar/items/foo',
				'site:com_foobar/item/foo',
			], 'Site app, plural view, no layout, no tpl, strict',
			],

			[
				'site', 'items', 'foo', 'bar', false, [
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			], 'Site app, plural view, no layout, tpl bar, no strict',
			],

			[
				'site', 'items', 'foo', 'bar', true, [
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/item/foo_bar',
			], 'Site app, plural view, no layout, tpl bar, strict',
			],

			// ====================

			[
				'admin', 'item', null, null, false, [
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default',
			], 'Admin app, singular view, no layout, no tpl, no strict',
			],

			[
				'admin', 'item', null, null, true, [
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default',
			], 'Admin app, singular view, no layout, no tpl, strict',
			],

			[
				'admin', 'item', null, 'bar', false, [
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
			], 'Admin app, singular view, no layout, tpl bar, no strict',
			],

			[
				'admin', 'item', null, 'bar', true, [
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/items/default_bar',
			], 'Admin app, singular view, no layout, tpl bar, strict',
			],

			[
				'admin', 'item', 'foo', null, false, [
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default',
			], 'Admin app, singular view, foo layout, no tpl, no strict',
			],

			[
				'admin', 'item', 'foo', null, true, [
				'admin:com_foobar/item/foo',
				'admin:com_foobar/items/foo',
			], 'Admin app, singular view, no layout, no tpl, strict',
			],

			[
				'admin', 'item', 'foo', 'bar', false, [
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
			], 'Admin app, singular view, no layout, tpl bar, no strict',
			],

			[
				'admin', 'item', 'foo', 'bar', true, [
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/items/foo_bar',
			], 'Admin app, singular view, no layout, tpl bar, strict',
			],

			// ------

			[
				'admin', 'items', null, null, false, [
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default',
			], 'Admin app, plural view, no layout, no tpl, no strict',
			],

			[
				'admin', 'items', null, null, true, [
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default',
			], 'Admin app, plural view, no layout, no tpl, strict',
			],

			[
				'admin', 'items', null, 'bar', false, [
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
			], 'Admin app, plural view, no layout, tpl bar, no strict',
			],

			[
				'admin', 'items', null, 'bar', true, [
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/item/default_bar',
			], 'Admin app, plural view, no layout, tpl bar, strict',
			],

			[
				'admin', 'items', 'foo', null, false, [
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default',
			], 'Admin app, plural view, foo layout, no tpl, no strict',
			],

			[
				'admin', 'items', 'foo', null, true, [
				'admin:com_foobar/items/foo',
				'admin:com_foobar/item/foo',
			], 'Admin app, plural view, no layout, no tpl, strict',
			],

			[
				'admin', 'items', 'foo', 'bar', false, [
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/items/foo',
				'admin:com_foobar/items/default_bar',
				'admin:com_foobar/items/default',
				'admin:com_foobar/item/foo_bar',
				'admin:com_foobar/item/foo',
				'admin:com_foobar/item/default_bar',
				'admin:com_foobar/item/default',
			], 'Admin app, plural view, no layout, tpl bar, no strict',
			],

			[
				'admin', 'items', 'foo', 'bar', true, [
				'admin:com_foobar/items/foo_bar',
				'admin:com_foobar/item/foo_bar',
			], 'Admin app, plural view, no layout, tpl bar, strict',
			],

			// ====================

			[
				'cli', 'item', null, null, false, [
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			], 'CLI app, singular view, no layout, no tpl, no strict',
			],

			[
				'cli', 'item', null, null, true, [
				'site:com_foobar/item/default',
				'site:com_foobar/items/default',
			], 'CLI app, singular view, no layout, no tpl, strict',
			],

			[
				'cli', 'item', null, 'bar', false, [
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			], 'CLI app, singular view, no layout, tpl bar, no strict',
			],

			[
				'cli', 'item', null, 'bar', true, [
				'site:com_foobar/item/default_bar',
				'site:com_foobar/items/default_bar',
			], 'CLI app, singular view, no layout, tpl bar, strict',
			],

			[
				'cli', 'item', 'foo', null, false, [
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
			], 'CLI app, singular view, foo layout, no tpl, no strict',
			],

			[
				'cli', 'item', 'foo', null, true, [
				'site:com_foobar/item/foo',
				'site:com_foobar/items/foo',
			], 'CLI app, singular view, no layout, no tpl, strict',
			],

			[
				'cli', 'item', 'foo', 'bar', false, [
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
			], 'CLI app, singular view, no layout, tpl bar, no strict',
			],

			[
				'cli', 'item', 'foo', 'bar', true, [
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/items/foo_bar',
			], 'CLI app, singular view, no layout, tpl bar, strict',
			],

			// ------

			[
				'cli', 'items', null, null, false, [
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			], 'CLI app, plural view, no layout, no tpl, no strict',
			],

			[
				'cli', 'items', null, null, true, [
				'site:com_foobar/items/default',
				'site:com_foobar/item/default',
			], 'CLI app, plural view, no layout, no tpl, strict',
			],

			[
				'cli', 'items', null, 'bar', false, [
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			], 'CLI app, plural view, no layout, tpl bar, no strict',
			],

			[
				'cli', 'items', null, 'bar', true, [
				'site:com_foobar/items/default_bar',
				'site:com_foobar/item/default_bar',
			], 'CLI app, plural view, no layout, tpl bar, strict',
			],

			[
				'cli', 'items', 'foo', null, false, [
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default',
			], 'CLI app, plural view, foo layout, no tpl, no strict',
			],

			[
				'cli', 'items', 'foo', null, true, [
				'site:com_foobar/items/foo',
				'site:com_foobar/item/foo',
			], 'CLI app, plural view, no layout, no tpl, strict',
			],

			[
				'cli', 'items', 'foo', 'bar', false, [
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/items/foo',
				'site:com_foobar/items/default_bar',
				'site:com_foobar/items/default',
				'site:com_foobar/item/foo_bar',
				'site:com_foobar/item/foo',
				'site:com_foobar/item/default_bar',
				'site:com_foobar/item/default',
			], 'CLI app, plural view, no layout, tpl bar, no strict',
			],

			[
				'cli', 'items', 'foo', 'bar', true, [
				'site:com_foobar/items/foo_bar',
				'site:com_foobar/item/foo_bar',
			], 'CLI app, plural view, no layout, tpl bar, strict',
			],
		];
	}

	public static function getTestGetTemplateOverridePath()
	{
		// $applicationType, $component, $absolute, $expected, $message
		return [
			['site', 'com_foobar', false, 'templates/system/html/com_foobar', 'site, com_foobar, relative path'],
			['site', 'com_foobar', true, JPATH_THEMES . '/system/html/com_foobar', 'site, com_foobar, absolute path'],
			[
				'site', 'media:/com_foobar', false, 'templates/system/media/com_foobar',
				'site, media:/com_foobar, relative path',
			],
			[
				'site', 'media:/com_foobar', true, JPATH_THEMES . '/system/media/com_foobar',
				'site, media:/com_foobar, absolute path',
			],
			[
				'admin', 'com_foobar', false, 'administrator/templates/system/html/com_foobar',
				'admin, com_foobar, relative path',
			],
			[
				'admin', 'media:/com_foobar', false, 'administrator/templates/system/media/com_foobar',
				'admin, media:/com_foobar, relative path',
			],
			['cli', 'com_foobar', false, '', 'cli, com_foobar, relative path'],
			['cli', 'com_foobar', true, '', 'cli, com_foobar, absolute path'],
			['cli', 'media:/com_foobar', false, '', 'cli, media:/com_foobar, relative path'],
			['cli', 'media:/com_foobar', true, '', 'cli, media:/com_foobar, absolute path'],
		];
	}

	public static function getTestLoadTranslations()
	{
		return [
			// $appType, $regularPath, $otherPath, $message
			['site', JPATH_ADMINISTRATOR, JPATH_SITE, "Site application, all languages must be loaded"],
			['admin', JPATH_SITE, JPATH_ADMINISTRATOR, "Admin application, all languages must be loaded"],
			['cli', JPATH_ADMINISTRATOR, JPATH_SITE, "CLI application, all languages must be loaded"],
			['exception', JPATH_ADMINISTRATOR, JPATH_SITE, "Exception application, all languages must be loaded"],
		];
	}

	public static function getTestAuthorizeAdmin()
	{
		return [
			// $appType, $auths, $expected, $message
			['admin', [], false, 'Admin app, no auths, not authorised'],
			['admin', ['foo.bar#com_foobar'], false, 'Admin app, other auths, not authorised'],
			['admin', ['core.admin#com_foobar'], true, 'Admin app, only core.admin, authorised'],
			['admin', ['core.manage#com_foobar'], true, 'Admin app, only core.manage, authorised'],
			['admin', ['core.manage#com_foobar', 'core.admin#com_foobar'], true, 'Admin app, all auths, authorised'],

			['site', [], true, 'Site app, no auths, implicitly authorised'],
			['site', ['foo.bar#com_foobar'], true, 'Site app, other auths, implicitly authorised'],
			['site', ['core.admin#com_foobar'], true, 'Site app, only core.admin, implicitly authorised'],
			['site', ['core.manage#com_foobar'], true, 'Site app, only core.manage, implicitly authorised'],
			[
				'site', ['core.manage#com_foobar', 'core.admin#com_foobar'], true,
				'Site app, all auths, implicitly authorised',
			],

			['cli', [], true, 'CLI app, no auths, implicitly authorised'],
			['cli', ['foo.bar#com_foobar'], true, 'CLI app, other auths, implicitly authorised'],
			['cli', ['core.admin#com_foobar'], true, 'CLI app, only core.admin, implicitly authorised'],
			['cli', ['core.manage#com_foobar'], true, 'CLI app, only core.manage, implicitly authorised'],
			[
				'cli', ['core.manage#com_foobar', 'core.admin#com_foobar'], true,
				'CLI app, all auths, implicitly authorised',
			],
		];
	}

	public static function getTestAuthorise()
	{
		return [
			// $appType, $auths, $assetName, $action, $expected, $message

			['site', [], null, 'foo.bar', false, 'Site app, authorise non-existent global action'],
			['site', ['baz.bat'], null, 'foo.bar', false, 'Site app, authorise wrong global action'],
			['site', ['foo.bar'], null, 'foo.bar', true, 'Site app, authorise existing global action'],
			['site', [], 'com_foobar', 'foo.bar', false, 'Site app, authorise non-existent component action'],
			[
				'site', ['baz.bat#com_foobar'], 'com_foobar', 'foo.bar', false,
				'Site app, authorise wrong component action',
			],
			[
				'site', ['foo.bar#com_foobar'], 'com_foobar', 'foo.bar', true,
				'Site app, authorise existing component action',
			],

			['admin', [], null, 'foo.bar', false, 'Admin app, authorise non-existent global action'],
			['admin', ['baz.bat'], null, 'foo.bar', false, 'Admin app, authorise wrong global action'],
			['admin', ['foo.bar'], null, 'foo.bar', true, 'Admin app, authorise existing global action'],
			['admin', [], 'com_foobar', 'foo.bar', false, 'Admin app, authorise non-existent component action'],
			[
				'admin', ['baz.bat#com_foobar'], 'com_foobar', 'foo.bar', false,
				'Admin app, authorise wrong component action',
			],
			[
				'admin', ['foo.bar#com_foobar'], 'com_foobar', 'foo.bar', true,
				'Admin app, authorise existing component action',
			],

			[
				'cli', [], null, 'foo.bar', true,
				'CLI app, authorise non-existent global action (explicitly allowed in CLI)',
			],
			[
				'cli', ['baz.bat'], null, 'foo.bar', true,
				'CLI app, authorise wrong global action (explicitly allowed in CLI)',
			],
			[
				'cli', ['foo.bar'], null, 'foo.bar', true,
				'CLI app, authorise existing global action (explicitly allowed in CLI)',
			],
			[
				'cli', [], 'com_foobar', 'foo.bar', true,
				'CLI app, authorise non-existent component action (explicitly allowed in CLI)',
			],
			[
				'cli', ['baz.bat#com_foobar'], 'com_foobar', 'foo.bar', true,
				'CLI app, authorise wrong component action (explicitly allowed in CLI)',
			],
			[
				'cli', ['foo.bar#com_foobar'], 'com_foobar', 'foo.bar', true,
				'CLI app, authorise existing component action (explicitly allowed in CLI)',
			],
		];
	}

	public static function getTestDate()
	{
		return [
			[
				[
					'case'           => 'GMT object, localised',
					'time'           => 'now',
					'offset'         => 'GMT',
					'locale'         => true,
					'intended_class' => 'FOF40\Date\DateDecorator',
				],
			],
			[
				[
					'case'           => 'GMT object, non-localised',
					'time'           => 'now',
					'offset'         => 'GMT',
					'locale'         => false,
					'intended_class' => 'FOF40\Date\Date',
				],
			],
		];
	}
}
