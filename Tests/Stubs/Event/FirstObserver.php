<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Event;

use FOF40\Event\Observer;

class FirstObserver extends Observer
{
	public $myId = 'one';

	public function returnConditional($stuff)
	{
		return ($stuff == $this->myId);
	}

	public function identifyYourself()
	{
		return $this->myId;
	}

	public function chain($stuff)
	{
		if ($stuff == $this->myId)
		{
			return $this->myId;
		}

		return null;
	}
}
