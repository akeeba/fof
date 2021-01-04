<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

defined('_JEXEC') or die;

if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	return;
}

class JFormFieldFofencryptedtoken extends JFormFieldText
{
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$ret = parent::setup($element, $value, $group); // TODO: Change the autogenerated stub

		// Do not display the token field when the user being edited is not the same as the logged in user
		$userId    = $this->form->getData()->get('id');

		if ($userId != \Joomla\CMS\Factory::getUser()->id)
		{
			$this->hidden = true;
		}

		return $ret;
	}


	protected function getInput()
	{
		// Do not display the token field when the user being edited is not the same as the logged in user
		if ($this->hidden)
		{
			return '';
		}

		$this->value = $this->getTokenForDisplay($this->value);

		return parent::getInput();
	}

	private function getTokenForDisplay($token)
	{
		$algo = $this->getAttribute('algo', 'sha256');

		try
		{
			$siteSecret = JFactory::getApplication()->get('secret');
		}
		catch (Exception $e)
		{
			$jConfig    = JFactory::getConfig();
			$siteSecret = $jConfig->get('secret');
		}

		$rawToken  = base64_decode($token);
		$tokenHash = hash_hmac($algo, $rawToken, $siteSecret);
		$userId    = $this->form->getData()->get('id');
		$message   = base64_encode("$algo:$userId:$tokenHash");

		if ($userId != \Joomla\CMS\Factory::getUser()->id)
		{
			$message = '';
		}

		return $message;
	}
}