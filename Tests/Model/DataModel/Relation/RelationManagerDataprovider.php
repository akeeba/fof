<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class RelationManagerDataprovider
{
	public static function getTestAddRelation()
	{
		$data[] = [
			[
				'name'        => 'wrong',
				'type'        => 'wrong',
				'parentModel' => null,
				'model'       => '',
			],
			[
				'case'      => 'Unknown relation type',
				'exception' => '\FOF40\Model\DataModel\Relation\Exception\RelationTypeNotFound',
				'relation'  => '',
			],
		];

		$data[] = [
			[
				'name'        => 'children',
				'type'        => 'hasMany',
				'parentModel' => '\Fakeapp\Site\Model\Datafoobars',
				'model'       => '',
			],
			[
				'case'      => 'Guessing the model name from the parent one (plural)',
				'exception' => '',
				'relation'  => 'children',
			],
		];

		$data[] = [
			[
				'name'        => 'child',
				'type'        => 'hasMany',
				'parentModel' => '\Fakeapp\Site\Model\Datafoobars',
				'model'       => '',
			],
			[
				'case'      => 'Guessing the model name from the parent one (singular)',
				'exception' => '',
				'relation'  => 'child',
			],
		];

		return $data;
	}

	public static function getTestSave()
	{
		$data[] = [
			[
				'name'      => 'wrong',
				'exception' => '',
			],
			[
				'case'      => 'Relation is not known',
				'exception' => 'FOF40\Model\DataModel\Relation\Exception\RelationNotFound',
				'save'      => false,
			],
		];

		$data[] = [
			[
				'name'      => 'test',
				'exception' => '',
			],
			[
				'case'      => 'Relation is known, saving just one',
				'exception' => '',
				'save'      => true,
			],
		];

		$data[] = [
			[
				'name'      => null,
				'exception' => '',
			],
			[
				'case'      => 'Relation is known, saving all of them',
				'exception' => '',
				'save'      => true,
			],
		];

		$data[] = [
			[
				'name'      => null,
				'exception' => 'notSupport',
			],
			[
				'case'      => 'Relation is known, saving all of them, but the relation does not support saving',
				'exception' => '',
				'save'      => false,
			],
		];

		$data[] = [
			[
				'name'      => null,
				'exception' => 'exception',
			],
			[
				'case'      => 'Relation is known, saving all of them, but the relation throws an exception',
				'exception' => 'exception',
				'save'      => false,
			],
		];

		return $data;
	}

	public static function getTest__call()
	{
		$data[] = [
			[
				'method'    => 'foobar',
				'arguments' => 0,
			],
			[
				'case'      => 'Method is not magic',
				'exception' => 'FOF40\Model\DataModel\Relation\Exception\RelationTypeNotFound',
				'get'       => false,
				'add'       => false,
				'name'      => '',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 0,
			],
			[
				'case'      => 'Method is a standard relation, 0 argument passed',
				'exception' => 'InvalidArgumentException',
				'get'       => false,
				'add'       => false,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 1,
			],
			[
				'case'      => 'Method is a standard relation, 1 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 2,
			],
			[
				'case'      => 'Method is a standard relation, 2 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 3,
			],
			[
				'case'      => 'Method is a standard relation, 3 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 4,
			],
			[
				'case'      => 'Method is a standard relation, 4 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 5,
			],
			[
				'case'      => 'Method is a standard relation, 5 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 6,
			],
			[
				'case'      => 'Method is a standard relation, 6 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 7,
			],
			[
				'case'      => 'Method is a standard relation, 7 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'hasMany',
				'arguments' => 8,
			],
			[
				'case'      => 'Method is a standard relation, 8 argument passed',
				'exception' => '',
				'get'       => false,
				'add'       => true,
				'name'      => 'hasMany',
			],
		];

		$data[] = [
			[
				'method'    => 'getPhone',
				'arguments' => 0,
			],
			[
				'case'      => 'Method is a "get" one, 0 argument passed',
				'exception' => '',
				'get'       => true,
				'add'       => false,
				'name'      => 'phone',
			],
		];

		$data[] = [
			[
				'method'    => 'getPhone',
				'arguments' => 1,
			],
			[
				'case'      => 'Method is a "get" one, 1 argument passed',
				'exception' => '',
				'get'       => true,
				'add'       => false,
				'name'      => 'phone',
			],
		];

		$data[] = [
			[
				'method'    => 'getPhone',
				'arguments' => 2,
			],
			[
				'case'      => 'Method is a "get" one, 2 argument passed',
				'exception' => '',
				'get'       => true,
				'add'       => false,
				'name'      => 'phone',
			],
		];

		$data[] = [
			[
				'method'    => 'getPhone',
				'arguments' => 3,
			],
			[
				'case'      => 'Method is a "get" one, 3 argument passed',
				'exception' => 'InvalidArgumentException',
				'get'       => false,
				'add'       => false,
				'name'      => 'phone',
			],
		];

		return $data;
	}

	public static function getTestIsMagicMethod()
	{
		$data[] = [
			[
				'method' => 'hasMany',
			],
			[
				'case'   => 'Method is the name of a standard type',
				'result' => true,
			],
		];

		$data[] = [
			[
				'method' => 'getFoobar',
			],
			[
				'case'   => 'Method is get-NameOfTheRelation- and the relation is set',
				'result' => true,
			],
		];

		$data[] = [
			[
				'method' => 'getDummy',
			],
			[
				'case'   => 'Method is get-NameOfTheRelation- and the relation is not set',
				'result' => false,
			],
		];

		$data[] = [
			[
				'method' => 'wrong',
			],
			[
				'case'   => 'Method is not magic',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestIsMagicProperty()
	{
		$data[] = [
			[
				'name' => 'foobar',
			],
			[
				'case'   => 'Property is magic',
				'result' => true,
			],
		];

		$data[] = [
			[
				'name' => 'wrong',
			],
			[
				'case'   => 'Property is not magic',
				'result' => false,
			],
		];

		return $data;
	}
}
