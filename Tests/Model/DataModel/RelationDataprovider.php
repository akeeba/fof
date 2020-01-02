<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class DataModelRelationDataprovider
{
	public static function getTestPush()
	{
		$data[] = [
			[
				'mock'      => [
					'names'   => ['test1', 'test2'],
					'touches' => [],
				],
				'relations' => null,
			],
			[
				'case'    => 'No touches, saving all relations',
				'save'    => ['test1', 'test2'],
				'touches' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'names'   => ['test1', 'test2'],
					'touches' => [],
				],
				'relations' => ['test1'],
			],
			[
				'case'    => 'No touches, saving some relations',
				'save'    => ['test1'],
				'touches' => [],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'names'   => ['test1', 'test2', 'children'],
					'touches' => ['children'],
				],
				'relations' => null,
			],
			[
				'case'    => 'With touches, saving all relations',
				'save'    => ['test1', 'test2', 'children'],
				'touches' => ['children'],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'names'   => ['test1', 'test2', 'children'],
					'touches' => ['children'],
				],
				'relations' => ['test1'],
			],
			[
				'case'    => 'With touches, saving some relations',
				'save'    => ['test1'],
				'touches' => ['children'],
			],
		];

		return $data;
	}

	public static function getTestEagerLoad()
	{
		$data[] = [
			[
				'mock'      => [
					'eager' => [],
				],
				'items'     => true,
				'relations' => [
					'test' => function () {
					},
				],
			],
			[
				'case'    => 'Passing a relation with callable callback, collection is not empty',
				'getData' => [
					'relation' => 'test',
					'callback' => 'function',
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'eager' => [],
				],
				'items'     => true,
				'relations' => [
					'test' => 'dummy',
				],
			],
			[
				'case'    => 'Passing a relation without a callable callback, collection is not empty',
				'getData' => [
					'relation' => 'dummy',
					'callback' => null,
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'eager' => [
						'test' => function () {
						},
					],
				],
				'items'     => true,
				'relations' => null,
			],
			[
				'case'    => 'Using the relation defined inside the object, collection is not empty',
				'getData' => [
					'relation' => 'test',
					'callback' => 'function',
				],
			],
		];

		$data[] = [
			[
				'mock'      => [
					'eager' => [
						'test' => function () {
						},
					],
				],
				'items'     => false,
				'relations' => null,
			],
			[
				'case'    => 'Collection is empty',
				'getData' => [],
			],
		];

		return $data;
	}

	public static function getTestHas()
	{
		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => false,
				],
				'relation' => 'posts',
				'method'   => '<>',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'Behaviors not loaded',
				'add'     => true,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [
						[
							'relation' => 'posts',
							'method'   => 'search',
							'value'    => 1,
							'operator' => '=',
						],
					],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '<>',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'Filter already set, not replacing it',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 1,
						'operator' => '=',
					],
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [
						[
							'relation' => 'posts',
							'method'   => 'search',
							'value'    => 1,
							'operator' => '=',
						],
					],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '<>',
				'values'   => 12,
				'replace'  => true,
			],
			[
				'case'    => 'Filter already set, replacing it',
				'add'     => false,
				'filters' => [
					1 => [
						'relation' => 'posts',
						'method'   => 'search',
						'operator' => '!=',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '<>',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '<> method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'lt',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'lt method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '<',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'le',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'le method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '<=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'gt',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'gt method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '>',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'ge',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'ge method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '>=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'eq',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'eq method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'neq',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'neq method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'ne',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'ne method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '<',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '< method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '<',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '!<',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '!< method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!<',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '<=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '<= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '<=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '!<=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '!<= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!<=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '>',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '> method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '>',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '!>',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '!> method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!>',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '>=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '>= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '>=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '!>=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '!>= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!>=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '!=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '!= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '= method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'like',
				'values'   => 'foobar',
				'replace'  => false,
			],
			[
				'case'    => 'like method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'partial',
						'operator' => 'like',
						'value'    => 'foobar',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '~',
				'values'   => 'foobar',
				'replace'  => false,
			],
			[
				'case'    => '~ method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'partial',
						'operator' => '~',
						'value'    => 'foobar',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '%',
				'values'   => 'foobar',
				'replace'  => false,
			],
			[
				'case'    => '%% method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'partial',
						'operator' => '%',
						'value'    => 'foobar',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '==',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '== method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'exact',
						'operator' => '==',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '=[]',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '=[] method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'exact',
						'operator' => '=[]',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '=()',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '=() method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'exact',
						'operator' => '=()',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'in',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'in method, values passed',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'exact',
						'operator' => 'in',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '[]',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '[] method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '[)',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '[) method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '(]',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '(] method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => [12],
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is an array with a single element',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => [12, 22],
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is an array, but no from/to keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'between',
						'operator' => '()',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => [12, 22, 'from' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is an array, but no "from" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'between',
						'operator' => '()',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => [12, 22, 'to' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is an array, but no "to" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'between',
						'operator' => '()',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '()',
				'values'   => [12, 22, 'from' => 5, 'to' => 7],
				'replace'  => false,
			],
			[
				'case'    => 'between method, values is an array, with "from/to" keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'between',
						'operator' => '()',
						'from'     => 5,
						'to'       => 7,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')[',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => ')[ method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '](',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => ']( method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '][',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '][ method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => [12],
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is an array with a single element',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '!=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => [12, 22],
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is an array, but no from/to keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'outside',
						'operator' => ')(',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => [12, 22, 'from' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is an array, but no "from" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'outside',
						'operator' => ')(',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => [12, 22, 'to' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is an array, but no "to" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'outside',
						'operator' => ')(',
						'from'     => 12,
						'to'       => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => ')(',
				'values'   => [12, 22, 'from' => 5, 'to' => 7],
				'replace'  => false,
			],
			[
				'case'    => 'outside method, values is an array, with "from/to" keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'outside',
						'operator' => ')(',
						'from'     => 5,
						'to'       => 7,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'every',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'every (interval) method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => [12],
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is an array with a single item',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'value'    => 12,
						'operator' => '=',
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => [12, 22],
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is an array, but no value/interval keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'interval',
						'operator' => '*=',
						'value'    => 12,
						'interval' => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => [12, 22, 'value' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is an array, but no "value" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'interval',
						'operator' => '*=',
						'value'    => 12,
						'interval' => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => [12, 22, 'interval' => 5],
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is an array, but no "interval" key',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'interval',
						'operator' => '*=',
						'value'    => 12,
						'interval' => 22,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '*=',
				'values'   => [12, 22, 'value' => 5, 'interval' => 7],
				'replace'  => false,
			],
			[
				'case'    => 'interval method, values is an array, with "value/interval" keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'interval',
						'operator' => '*=',
						'value'    => 5,
						'interval' => 7,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '?=',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => '?= method, values is not an array',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'operator' => '?=',
						'value'    => 12,
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '?=',
				'values'   => [12],
				'replace'  => false,
			],
			[
				'case'    => '?= method, values is an array with a single item',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'operator' => '?=',
						'value'    => [12],
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => '?=',
				'values'   => [12, 22],
				'replace'  => false,
			],
			[
				'case'    => '?= method, values is an array with no "operator/value" keys',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'operator' => '?=',
						'value'    => [12, 22],
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'callback',
				'values'   => function () {
				},
				'replace'  => false,
			],
			[
				'case'    => 'callback method, values is a callable function',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'callback',
						'operator' => 'callback',
						'value'    => function () {
						},
					],
				],
			],
		];

		$data[] = [
			[
				'mock'     => [
					'filters'  => [],
					'hasClass' => true,
				],
				'relation' => 'posts',
				'method'   => 'callback',
				'values'   => 12,
				'replace'  => false,
			],
			[
				'case'    => 'callback method, values is NOT a callable function',
				'add'     => false,
				'filters' => [
					[
						'relation' => 'posts',
						'method'   => 'search',
						'operator' => '=',
						'value'    => 1,
					],
				],
			],
		];

		return $data;
	}
}
