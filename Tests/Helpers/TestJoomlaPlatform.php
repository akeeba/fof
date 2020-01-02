<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers;

use FOF40\Input\Input;
use FOF40\Platform\Joomla\Platform as PlatformJoomla;
use Joomla\CMS\Language\Language;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;

/**
 * A specialised Joomla! platform abstraction class which can lie about running under CLI, frontend or backend.
 */
class TestJoomlaPlatform extends PlatformJoomla
{
	/** @var bool Should this platform instance report running under CLI mode? */
	public static $isCli = false;

	/** @var bool Should this platform instance report running under Joomla! backend? */
	public static $isAdmin = false;

	/** @var bool Should this platform instance report running under Joomla! backend? */
	public static $isApi= false;

	/** @var string|null The template name reported by this class */
	public static $template = null;

	/** @var array|null The template suffixes to return e.g. ['.j32', '.j3'] and so on */
	public static $templateSuffixes = null;

	/** @var array|null The platform layout directories to return */
	public static $baseDirs = null;

	/** @var User|null The current user */
	public static $user = null;

	public static $uriBase = null;

	public static $uriRoot = null;

	/** @var \Closure Supply a closure to perform additional checks */
	public static $authorise = null;

	/** @var \Closure Supply a closure to perform additional checks */
	public static $getUserStateFromRequest = null;

	/** @var \Closure Supply a closure to perform additional checks */
	public static $runPlugins = null;

	/** @var  \Closure Supply a closure to mock the language object */
	public static $language;

	/** @var  \Closure Supply a closure to mock the config object */
	public static $config;

	/** @var  \Closure Supply a closure to mock the result of getComponentBaseDirs($component) */
	public static $getComponentBaseDirs;

	/** @var  \Closure Supply a closure to mock the result of getTemplateOverridePath($component, $absolute) */
	public static $getTemplateOverridePath;

	/**
	 * Resets all the mock variables to their default value
	 */
	public function reset()
	{
		static::$isCli                   = false;
		static::$isAdmin                 = false;
		static::$template                = null;
		static::$templateSuffixes        = null;
		static::$baseDirs                = null;
		static::$user                    = null;
		static::$uriBase                 = null;
		static::$uriRoot                 = null;
		static::$authorise               = null;
		static::$runPlugins              = null;
		static::$config                  = null;
		static::$getUserStateFromRequest = null;
		static::$getComponentBaseDirs    = null;
		static::$getTemplateOverridePath = null;
	}

	public function getUser(?int $id = null): User
	{
		if (isset(static::$user))
		{
			return static::$user;
		}

		return parent::getUser($id);
	}

	public function URIbase(bool $pathonly = false): string
	{
		if (isset(static::$uriBase))
		{
			return static::$uriBase;
		}

		return parent::URIbase($pathonly);
	}

	public function URIroot(bool $pathonly = false, ?string $path = null): string
	{
		if (isset(static::$uriRoot))
		{
			return static::$uriRoot;
		}

		return parent::URIroot($pathonly, $path);
	}

	public function authorise(string $action, ?string $assetname = null): bool
	{
		if (is_callable(static::$authorise))
		{
			return call_user_func_array(static::$authorise, [$action, $assetname]);
		}

		return parent::authorise($action, $assetname);
	}

	/**
	 * Main function to detect if we're running in a CLI environment and we're admin. This method is designed to lie.
	 *
	 * @return  array  isCLI and isAdmin. It's not an associative array, so we can use list.
	 */
	protected function isCliAdminApi(): array
	{
		return [self::$isCli, self::$isAdmin, self::$isApi];
	}

	/**
	 * Returns the application's template name
	 *
	 * @param   boolean|array $params An optional associative array of configuration settings
	 *
	 * @return  string  The template name. System is the fallback.
	 */
	public function getTemplate(?array $params = null): string
	{
		if (is_null(self::$template))
		{
			return parent::getTemplate($params);
		}

		return self::$template;
	}

	public function getTemplateSuffixes(): array
	{
		if (is_null(self::$templateSuffixes))
		{
			return parent::getTemplateSuffixes();
		}

		return self::$templateSuffixes;
	}

	public function getPlatformBaseDirs(): array
	{
		if (is_null(self::$baseDirs))
		{
			return parent::getPlatformBaseDirs();
		}

		return self::$baseDirs;
	}

	/**
	 * Sync the isCli / isAdmin with the real values
	 */
	public function resetIsCliAdmin()
	{
		list(self::$isCli, self::$isAdmin) = parent::isCliAdminApi();
	}

	public function getUserStateFromRequest(string $key, string $request, Input $input, $default = null, string $type = 'none', bool $setUserState = true)
	{
		if (is_callable(static::$getUserStateFromRequest))
		{
			return call_user_func_array(static::$getUserStateFromRequest, [
				$key, $request, $input, $default, $type, $setUserState,
			]);
		}

		return parent::getUserStateFromRequest($key, $request, $input, $default, $type, $setUserState);
	}

	public function runPlugins(string $event, array $data = []): array
	{
		if (is_callable(static::$runPlugins))
		{
			return call_user_func_array(static::$runPlugins, [$event, $data]);
		}

		return parent::runPlugins($event, $data);
	}

	public function getLanguage(): Language
	{
		if (is_callable(static::$language))
		{
			return call_user_func(static::$language);
		}

		return parent::getLanguage();
	}

	public function getConfig(): Registry
	{
		if (is_callable(static::$config))
		{
			return call_user_func(static::$config);
		}

		return parent::getConfig();
	}

	public function getComponentBaseDirs(string $component): array
	{
		if (is_callable(static::$getComponentBaseDirs))
		{
			return call_user_func(static::$getComponentBaseDirs, $component);
		}

		return parent::getComponentBaseDirs($component);
	}

	public function getTemplateOverridePath(string $component, bool $absolute = true): string
	{
		if (is_callable(static::$getTemplateOverridePath))
		{
			return call_user_func(static::$getTemplateOverridePath, $component, $absolute);
		}

		return parent::getTemplateOverridePath($component, $absolute);
	}
}
