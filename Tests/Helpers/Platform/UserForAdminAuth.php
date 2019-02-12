<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\Helpers\Platform;


use Joomla\CMS\User\User;

class UserForAdminAuth extends User
{
	public $allowedAuths = []; // e.g. core.admin#com_foobar

	public function authorise($action, $assetname = null)
	{
		$signature = $action . (is_null($assetname) ? '' : ('#' . $assetname));

		return in_array($signature, $this->allowedAuths);
	}
}
