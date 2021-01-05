<?php


abstract class MasterFakeClass
{
	public static function __callStatic($name, $arguments)
	{
		// Does sod all
	}

	public function __call($name, $arguments)
	{
		// Does sod all
	}

	public function __get($name)
	{
		return null;
	}

	public function __set($name, $value)
	{
		// Does sod all
	}
}