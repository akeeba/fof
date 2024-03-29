<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Model\DataModel\Filter;

use FOF40\Model\DataModel\Filter\AbstractFilter;

class FilterStub extends AbstractFilter
{
	public function partial($value)
	{
		return '';
	}

	public function between($from, $to, $include = true)
	{
		return '';
	}

	public function outside($from, $to, $include = false)
	{
		return '';
	}

	public function interval($from, $interval)
	{
		return '';
	}

	public function range($from, $to, $include = true)
	{
		return '';
	}

	public function modulo($from, $interval, $include = true)
	{
		return '';
	}


}
