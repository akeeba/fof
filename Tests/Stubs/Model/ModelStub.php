<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Stubs\Model;

use FOF40\Container\Container;
use FOF40\Model\Model;

class ModelStub extends Model
{
	private $methods = [];

	/**  @var null The container passed in the construct */
	public $passedContainer = null;

	/** @var array Simply counter to check if a specific function is called */
	public $methodCounter = [
		'getClone'   => 0,
		'savestate'  => 0,
		'clearState' => 0,
		'clearInput' => 0,
	];

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

		return parent::__call($method, $args);
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
			$func = $this->methods['getName'];

			return call_user_func_array($func, []);
		}

		return parent::getName();
	}

	/**
	 * Method to test if the view can invoke model methods using the syntax $view->get('foobar');
	 */
	public function getFoobar()
	{
		return 'ok';
	}

	/**
	 * Method to test if the view can invoke model methods using the syntax $view->get('dummy');
	 */
	public function dummy()
	{
		return 'ok';
	}

	public function clearState()
	{
		$this->methodCounter['clearState']++;

		return parent::clearState();
	}

	public function getClone()
	{
		$this->methodCounter['getClone']++;

		return parent::getClone();
	}

	public function savestate($newState)
	{
		$this->methodCounter['savestate']++;

		return parent::savestate($newState);
	}
}

