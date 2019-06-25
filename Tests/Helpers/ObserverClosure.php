<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\Helpers;

use FOF40\Event\Observable;
use FOF40\Event\Observer;

/**
 * This observer allows us to attach anonymous functions to the event, so we can dynamically perform all the tests we need
 *
 * @package Awf\Tests\Stubs\Utils
 */
class ObserverClosure extends Observer
{
	protected $methods = [];

	/**
	 * Assigns callback functions to the class, the $methods array should be an associative one, where
	 * the keys are the method names, while the values are the closure functions, e.g.
	 *
	 * array(
	 *    'onBeforeMove' => function(){ return 'Foobar'; }
	 * )
	 *
	 * @param Observable $subject
	 * @param array      $methods
	 */
	public function __construct(Observable &$subject, array $methods = [])
	{
		parent::__construct($subject);

		foreach ($methods as $method => $function)
		{
			$this->methods[$method] = $function;
		}
	}

	/*
	 * The layout object will perform a "method_exists" check, so we have to create them, otherwise they won't be invoked
	 */
	public function onBeforeMove(&$subject, &$delta, &$where)
	{
		if (isset($this->methods['onBeforeMove']))
		{
			$func = $this->methods['onBeforeMove'];

			return call_user_func_array($func, [&$subject, &$delta, &$where]);
		}
	}

	public function onAfterMove()
	{
		if (isset($this->methods['onAfterMove']))
		{
			$func = $this->methods['onAfterMove'];

			return call_user_func_array($func, []);
		}
	}

	public function onBeforeLoad(&$subject, &$keys)
	{
		if (isset($this->methods['onBeforeLoad']))
		{
			$func = $this->methods['onBeforeLoad'];

			return call_user_func_array($func, [&$subject, &$keys]);
		}
	}

	public function onAfterLoad(&$subject, $success, $keys)
	{
		if (isset($this->methods['onAfterLoad']))
		{
			$func = $this->methods['onAfterLoad'];

			return call_user_func_array($func, [&$subject, $success, $keys]);
		}
	}

	public function onBeforeSave(&$subject, &$data)
	{
		if (isset($this->methods['onBeforeSave']))
		{
			$func = $this->methods['onBeforeSave'];

			return call_user_func_array($func, [&$subject, &$data]);
		}
	}

	public function onAfterSave()
	{
		if (isset($this->methods['onAfterSave']))
		{
			$func = $this->methods['onAfterSave'];

			return call_user_func_array($func, []);
		}
	}

	public function onBeforeCreate(&$subject, &$dataObject)
	{
		if (isset($this->methods['onBeforeCreate']))
		{
			$func = $this->methods['onBeforeCreate'];

			return call_user_func_array($func, [&$subject, &$dataObject]);
		}
	}

	public function onAfterCreate()
	{
		if (isset($this->methods['onAfterCreate']))
		{
			$func = $this->methods['onAfterCreate'];

			return call_user_func_array($func, []);
		}
	}

	public function onBeforeUpdate(&$subject, &$dataObject)
	{
		if (isset($this->methods['onBeforeUpdate']))
		{
			$func = $this->methods['onBeforeUpdate'];

			return call_user_func_array($func, [&$subject, &$dataObject]);
		}
	}

	public function onAfterUpdate()
	{
		if (isset($this->methods['onAfterUpdate']))
		{
			$func = $this->methods['onAfterUpdate'];

			return call_user_func_array($func, []);
		}
	}
}
