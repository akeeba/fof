<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace Fakeapp\Site\Model;

use FOF40\Container\Container;
use FOF40\Model\DataModel;

class Children extends DataModel
{
	public function __construct(Container $container, array $config = [])
	{
		// I have to manually disable autoChecks, otherwise FOF will try to search for the form, raising
		// a fatal error
		$config['autoChecks'] = false;

		parent::__construct($container, $config);
	}
}
