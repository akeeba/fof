<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Model\DataModel\Behaviour;

use FOF30\Event\Observer;
use FOF30\Model\DataModel;
use JDatabaseQuery;

defined('_JEXEC') or die;

/**
 * FOF model behavior class to filter front-end access to items
 * based on the language.
 *
 * @since    2.1
 */
class Language extends Observer
{
	/**
	 * This event runs before we have built the query used to fetch a record
	 * list in a model. It is used to blacklist the language filter
	 *
	 * @param   F0FModel        &$model  The model which calls this event
	 * @param   JDatabaseQuery  &$query  The model which calls this event
	 *
	 * @return  void
	 */
	public function onBeforeBuildQuery(&$model, &$query)
	{
		if ($model->getContainer()->platform->isFrontend())
		{
			$model->blacklistFilters('language');
		}
	}

	/**
	 * This event runs after we have built the query used to fetch a record
	 * list in a model. It is used to apply automatic query filters.
	 *
	 * @param   DataModel      &$model The model which calls this event
	 * @param   JDatabaseQuery &$query The query we are manipulating
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(&$model, &$query)
	{
		$platform = $model->getContainer()->platform;

		// Make sure the field actually exists
		if (!$model->hasField('language'))
		{
			return;
		}

		/** @var \JApplicationSite $app */
		$app = \JFactory::getApplication();
		$hasLanguageFilter = method_exists($app, 'getLanguageFilter');

		if ($hasLanguageFilter)
		{
			$hasLanguageFilter = $app->getLanguageFilter();
		}

		if (!$hasLanguageFilter)
		{
			return;
		}

		$lang_filter_plugin = \JPluginHelper::getPlugin('system', 'languagefilter');
		$lang_filter_params = new \JRegistry($lang_filter_plugin->params);

		$languages = array('*');

		if ($lang_filter_params->get('remove_default_prefix'))
		{
			// Get default site language
			$lg = $platform->getLanguage();
			$languages[] = $lg->getTag();
		}
		else
		{
			$languages[] = \JFactory::getApplication()->input->getCmd('language', '*');
		}

		// Filter out double languages
		$languages = array_unique($languages);

		// And filter the query output by these languages
		$db = $model->getDbo();
		$languages = array_map(array($db, 'quote'), $languages);
		$fieldName = $model->getFieldAlias('language');

		$query->where($db->qn($fieldName) . ' IN(' . implode(', ', $languages) . ')');
	}

	/**
	 * The event runs after DataModel has retrieved a single item from the database. It is used to apply automatic
	 * filters.
	 *
	 * @param   DataModel &$model  The model which was called
	 * @param   Array     &$keys   The keys used to locate the record which was loaded
	 *
	 * @return  void
	 */
	public function onAfterLoad(&$model, &$keys)
	{
		// Make sure we have a DataModel
		if (!($model instanceof DataModel))
		{
			return;
		}

		// Make sure the field actually exists
		if (!$model->hasField('language'))
		{
			return;
		}

		// Make sure it is a multilingual site and get a list of languages
		/** @var \JApplicationSite $app */
		$app = \JFactory::getApplication();
		$hasLanguageFilter = method_exists($app, 'getLanguageFilter');

		if ($hasLanguageFilter)
		{
			$hasLanguageFilter = $app->getLanguageFilter();
		}

		if (!$hasLanguageFilter)
		{
			return;
		}

		$lang_filter_plugin = \JPluginHelper::getPlugin('system', 'languagefilter');
		$lang_filter_params = new \JRegistry($lang_filter_plugin->params);

		$languages = array('*');

		if ($lang_filter_params->get('remove_default_prefix'))
		{
			// Get default site language
			$lg = $model->getContainer()->platform->getLanguage();
			$languages[] = $lg->getTag();
		}
		else
		{
			$languages[] = \JFactory::getApplication()->input->getCmd('language', '*');
		}

		// Filter out double languages
		$languages = array_unique($languages);

		// Filter by language
		if (!in_array($model->getFieldValue('language'), $languages))
		{
			$model->reset();
		}
	}
}