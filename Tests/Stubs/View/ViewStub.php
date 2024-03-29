<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\View;

use FOF40\Container\Container;
use FOF40\View\View;

class ViewStub extends View
{
	private $methods = [];

	protected $name = 'nestedset';

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
			$this->methods[$method] = $function;
		}

		parent::__construct($container, $config);
	}

	public function __call($method, $args)
	{
		if (isset($this->methods[$method]))
		{
			$func = $this->methods[$method];

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
	public function getName()
	{
		if (isset($this->methods['getName']))
		{
			if ($this->methods['getName'] == 'parent')
			{
				return parent::getName();
			}

			$func = $this->methods['getName'];

			return call_user_func_array($func, []);
		}

		return $this->name;
	}

	/**
	 * I have to hardcode these function since sometimes we do a method_exists check and that won't
	 * trigger __call
	 *
	 * @return mixed
	 */
	public function onBeforeDummy($tpl = null)
	{
		if (isset($this->methods['onBeforeDummy']))
		{
			$func = $this->methods['onBeforeDummy'];

			return call_user_func_array($func, [$this, $tpl]);
		}

		return true;
	}

	public function onAfterDummy($tpl = null)
	{
		if (isset($this->methods['onAfterDummy']))
		{
			$func = $this->methods['onAfterDummy'];

			return call_user_func_array($func, [$this, $tpl]);
		}

		return true;
	}
}

