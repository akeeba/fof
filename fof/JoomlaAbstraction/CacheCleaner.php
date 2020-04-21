<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\JoomlaAbstraction;

use Exception;
use Joomla\CMS\Cache\Cache;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

/**
 * A utility class to help you quickly clean the Joomla! cache
 */
class CacheCleaner
{
	/**
	 * Clears the com_modules and com_plugins cache. You need to call this whenever you alter the publish state or
	 * parameters of a module or plugin from your code.
	 *
	 * @return  void
	 */
	public static function clearPluginsAndModulesCache(): void
	{
		self::clearPluginsCache();
		self::clearModulesCache();
	}

	/**
	 * Clears the com_plugins cache. You need to call this whenever you alter the publish state or parameters of a
	 * plugin from your code.
	 *
	 * @return  void
	 */
	public static function clearPluginsCache(): void
	{
		self::clearCacheGroups(['com_plugins'], [0, 1]);
	}

	/**
	 * Clears the com_modules cache. You need to call this whenever you alter the publish state or parameters of a
	 * module from your code.
	 *
	 * @return  void
	 */
	public static function clearModulesCache(): void
	{
		self::clearCacheGroups(['com_modules'], [0, 1]);
	}

	/**
	 * Clears the specified cache groups.
	 *
	 * @param   array        $clearGroups   Which cache groups to clear. Usually this is com_yourcomponent to clear your
	 *                                      component's cache.
	 * @param   array        $cacheClients  Which cache clients to clear. 0 is the back-end, 1 is the front-end. If you
	 *                                      do not specify anything, both cache clients will be cleared.
	 * @param   string|null  $event         An event to run upon trying to clear the cache. Empty string to disable. If
	 *                                      NULL and the group is "com_content" I will trigger onContentCleanCache.
	 *
	 * @return  void
	 */
	public static function clearCacheGroups(array $clearGroups, array $cacheClients = [0, 1], ?string $event = null): void
	{
		try
		{
			$app = JoomlaFactory::getApplication();
		}
		catch (Exception $e)
		{
			return;
		}

		foreach ($clearGroups as $group)
		{
			foreach ($cacheClients as $client_id)
			{
				try
				{
					$options = [
						'defaultgroup' => $group,
						'cachebase'    => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $app->get('cache_path', JPATH_SITE . '/cache'),
						'result'       => true,
					];

					$cache = Cache::getInstance('callback', $options);
					/** @noinspection PhpUndefinedMethodInspection Available via __call(), not tagged in Joomla core */
					$cache->clean();
				}
				catch (\Exception $e)
				{
					$options['result'] = false;
				}

				/**
				 * Call Joomla's cache cleaning plugin event (e.g. onContentCleanCache) as well.
				 *
				 * @see BaseDatabaseModel::cleanCache()
				 */
				if (!empty($event))
				{
					$app->triggerEvent($event, $options);
				}
				/**
				 * If you're cleaning com_content and you have passed no event name I will use onContentCleanCache.
				 */
				elseif (($group == 'com_content') && ($event !== ''))
				{
					$app->triggerEvent('onContentCleanCache', $options);
				}
			}
		}
	}
}
