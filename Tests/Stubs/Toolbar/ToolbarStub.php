<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Toolbar;

use FOF40\Toolbar\Toolbar;

class ToolbarStub extends Toolbar
{
	/** @var array Simply counter to check if a specific function is called */
	public $methodCounter = [];

	public function onFoobarsDummy()
	{
		if (isset($this->methodCounter['onFoobarsDummy']))
		{
			$this->methodCounter['onFoobarsDummy']++;
		}
		else
		{
			$this->methodCounter['onFoobarsDummy'] = 1;
		}
	}

	public function onViews()
	{
		if (isset($this->methodCounter['onViews']))
		{
			$this->methodCounter['onViews']++;
		}
		else
		{
			$this->methodCounter['onViews'] = 1;
		}
	}

	public function onTask()
	{
		if (isset($this->methodCounter['onTask']))
		{
			$this->methodCounter['onTask']++;
		}
		else
		{
			$this->methodCounter['onTask'] = 1;
		}
	}
}
