<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Generator\Command\Generate\Layout;

use FOF30\Generator\Command\Command as Command;

class ItemLayout extends LayoutBase
{
	public function execute()
    {
		$this->createView('item');
	}
}