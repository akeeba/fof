<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
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
