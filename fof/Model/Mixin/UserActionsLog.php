<?php
/**
 * @package     FOF
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Model\Mixin;

use FOF30\Container\Container;

defined('_JEXEC') or die;

\JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_actionlogs/models', 'ActionlogsModel');

/**
 * @method	Container getContainer()
 */
trait UserActionsLog
{
	protected $logActions = false;

	protected function enableLogging()
	{
		$this->logActions = true;

		// If we're on Joomla lower than 3.9 forcibly disable logging
		if (version_compare(JVERSION, '3.9', 'lt'))
		{
			$this->logActions = false;
		}

		return $this->logActions;
	}

	public function logAction($message, $logText = '', $extension = '')
	{
		// No support/Not enabled, stop here
		if (!$this->logActions)
		{
			return;
		}

		// Sanity checks
		$required_keys = ['action'];

		foreach ($required_keys as $required)
		{
			if (!isset($message[$required]))
			{
				throw new \LogicException(sprintf('Missing required key %s in order to log user action', $required));
			}
		}

		// No extension? Let's fetch it from the application
		if (!$extension)
		{
			$extension = $this->getContainer()->componentName;
		}

		$container 	= $this->getContainer();
		$user		= $container->platform->getUser();

		if (!array_key_exists('username', $message))
		{
			$message['username'] = $user->username;
		}

		if (!array_key_exists('accountlink', $message))
		{
			$message['accountlink'] = 'index.php?option=com_users&task=user.edit&id=' . $user->id;
		}

		// No Log Text? Let's build it from the data we have
		if (!$logText)
		{
			$view	 = $container->input->getCmd('view');
			$key 	 = $extension . '_LOGS_' . $container->inflector->singularize($view) .'_'.$message['action'];
			$logText = strtoupper($key);
		}

		/** @var \ActionlogsModelActionlog $model **/
		$model = \JModelLegacy::getInstance('Actionlog', 'ActionlogsModel');
		$model->addLog(array($message), $logText, $extension);
	}
}