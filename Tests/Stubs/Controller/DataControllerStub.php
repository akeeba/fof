<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Controller;

use FOF40\Container\Container;
use FOF40\Controller\DataController;

class DataControllerStub extends DataController
{
	private $mockedMethods = [];
	protected $name = 'dummycontroller';

	/**
	 * Assigns callback functions to the class, the $methods array should be an associative one, where
	 * the keys are the method names, while the values are the closure functions, e.g.
	 *
	 * array(
	 *    'foobar' => function(){ return 'Foobar'; }
	 * )
	 *
	 * @param           $container
	 * @param array     $config
	 * @param array     $methods
	 */
	public function __construct(Container $container, array $config = [], array $methods = [])
	{
		foreach ($methods as $method => $function)
		{
			$this->mockedMethods[$method] = $function;
		}

		parent::__construct($container, $config);
	}

	public function __call($method, $args)
	{
		if (isset($this->mockedMethods[$method]))
		{
			$func = $this->mockedMethods[$method];

			// Let's pass an instance of ourself, so we can manipulate other closures
			array_unshift($args, $this);

			return call_user_func_array($func, $args);
		}
	}

	/**
	 * A mocked object will have a random name, that won't match the regex expression in the parent.
	 * To prevent exceptions, we have to manually set the name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		if (isset($this->mockedMethods['getName']))
		{
			$func = $this->mockedMethods['getName'];

			return call_user_func_array($func, []);
		}

		return parent::getName();
	}
}
