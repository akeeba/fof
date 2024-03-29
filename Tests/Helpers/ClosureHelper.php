<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers;

class ClosureHelper
{
	private $mockedMethods = [];

	/**
	 * Assigns callback functions to the class, the $methods array should be an associative one, where
	 * the keys are the method names, while the values are the closure functions, e.g.
	 *
	 * array(
	 *    'foobar' => function(){ return 'Foobar'; }
	 * )
	 *
	 * @param array $methods
	 */
	public function __construct(array $methods = [])
	{
		foreach ($methods as $method => $function)
		{
			$this->mockedMethods[$method] = $function;
		}
	}

	public function __call($method, $args)
	{
		if (isset($this->mockedMethods[$method]))
		{
			$func = $this->mockedMethods[$method];

			// Let's pass an instance of ourselves, so we can manipulate other closures
			array_unshift($args, $this);

			return call_user_func_array($func, $args);
		}

		return null;
	}
}
