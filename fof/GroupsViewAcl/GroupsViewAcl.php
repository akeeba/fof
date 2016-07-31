<?php
/**
 * @package    FOF
 * @copyright  2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 2 or later
 */

namespace FOF30\GroupsViewAcl;

use /** @noinspection PhpUndefinedClassInspection */
	\JFactory;
use /** @noinspection PhpUndefinedClassInspection */
	\JText;
/**
 * Collection of methods to extend handling of groups
 *
 * @package  FOF30\GroupsViewAcl
 *
 * @since    3.0
 */
class GroupsViewAcl
{
	/**
	 * Get the GroupId's corresponding to the grop names
	 *
	 * @param   array|string  $groupNames  empty strings are ignored
	 *
	 * @return array
	 *
	 * @throws \RuntimeException
	 *
	 * @since version
	 */
	public static function getGroupIdsByNames($groupNames)
	{
		/** @noinspection PhpUndefinedClassInspection */
		$db = JFactory::getDbo();

		if (is_string($groupNames))
		{
			// Convert to string to array
			$groupNamesArr = ($groupNames === '') ? array() : array($groupNames);
		}
		elseif (is_array($groupNames))
		{
			$groupNamesArr = (array) $groupNames;
		}
		else
		{
			/** @noinspection PhpUndefinedClassInspection */
			throw new \RuntimeException(JText::_('LIB_FOF_GROUPSVIEWACL_INVALID_GROUPNAMES_TYPE'));
			/** @noinspection PhpUnreachableStatementInspection we need to please codesniffer */
			$groupNamesArr = array();
		}

		$where = array();

		foreach ($groupNamesArr as $name)
		{
			if ($name !== '')
			{
				$where[] = $db->quoteName('title') . ' like ' . $db->quote($name);
			}
		}

		$where = implode(' OR ', $where);

		$query = $db->getQuery(true);
		$query
			->select($db->quoteName('id'))
			->from($db->quoteName('#__usergroups'));

		/** @noinspection IsEmptyFunctionUsageInspection */
		if (!empty($where))
		{
			$query->where($where);
		}

		$db->setQuery($query);

		return $db->loadColumn();
	}
}
