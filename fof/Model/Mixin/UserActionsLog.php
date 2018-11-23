<?php
/**
 * @package     FOF
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Model\Mixin;

use FOF30\Container\Container;

defined('_JEXEC') or die;

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
		$required_keys = ['action', 'type', 'title'];

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

		$user = $this->getContainer()->platform->getUser();

		if (!array_key_exists('userid', $message))
		{
			$message['userid'] = $user->id;
		}

		if (!array_key_exists('username', $message))
		{
			$message['username'] = $user->username;
		}

		if (!array_key_exists('accountlink', $message))
		{
			$message['accountlink'] = 'index.php?option=com_users&task=user.edit&id=' . $user->id;
		}

		$message['type'] = strtoupper($message['type']);

		// No Log Text? Let's build it from the data we have
		if (!$logText)
		{
			$key 	 = $extension . '_LOGS_' . $this->getContainer()->input->getCmd('view') .'_'.$message['type'];
			$logText = strtoupper($key);
		}

		/** @var \ActionlogsModelActionlog $model **/
		$model = \JModelLegacy::getInstance('Actionlog', 'ActionlogsModel');
		$model->addLog(array($message), $logText, $extension);
	}
}