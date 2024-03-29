<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers;

use FOF40\Container\Container;

/**
 * A specialised container for use in Unit Testing
 */
class TestContainer extends Container
{
	public function __construct(array $values = [])
	{
		if (!isset($values['componentName']))
		{
			$values['componentName'] = 'com_fakeapp';
		}

		if (!isset($values['platform']))
		{
			$values['platform'] = function (Container $c) {
				return new TestJoomlaPlatform($c);
			};
		}

		return parent::__construct($values);
	}
}
