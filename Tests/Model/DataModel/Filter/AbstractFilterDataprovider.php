<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class AbstractFilterDataprovider
{
	public static function getTest__constructException()
	{
		// Invalid type
		$data[] = [
			[
				'field' => null,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => 1,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => true,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => 'asd',
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => [1],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'type' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name' => 'test',
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'field' => 'test',
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name'  => 'test',
					'field' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name'  => null,
					'field' => 'test',
				],
			],
		];

		return $data;
	}

	public static function getTestIsEmpty()
	{
		$data[] = [
			[
				'null'  => null,
				'value' => null,
			],
			[
				'case'   => 'Value: NULL, Null_value: NULL',
				'result' => true,
			],
		];

		$data[] = [
			[
				'null'  => null,
				'value' => 55,
			],
			[
				'case'   => 'Value: 55, Null_value: NULL',
				'result' => false,
			],
		];

		$data[] = [
			[
				'null'  => -1,
				'value' => null,
			],
			[
				'case'   => 'Value: NULL, Null_value: -1',
				'result' => true,
			],
		];

		$data[] = [
			[
				'null'  => -1,
				'value' => 'test',
			],
			[
				'case'   => 'Value: test, Null_value: -1',
				'result' => false,
			],
		];

		$data[] = [
			[
				'null'  => 'test',
				'value' => 'test',
			],
			[
				'case'   => 'Value: test, Null_value: test',
				'result' => true,
			],
		];

		return $data;
	}

	public function getTestExact()
	{
		$data[] = [
			[
				'mock'  => [
					'isEmpty' => true,
				],
				'value' => '',
			],
			[
				'case'   => 'Passed value is empty',
				'name'   => false,
				'search' => false,
				'result' => '',
			],
		];

		$data[] = [
			[
				'mock'  => [
					'isEmpty' => false,
				],
				'value' => 'test',
			],
			[
				'case'   => 'Passed value is not empty',
				'name'   => false,
				'search' => true,
				'result' => 'search',
			],
		];

		$data[] = [
			[
				'mock'  => [
					'isEmpty' => false,
				],
				'value' => ['foo', 'bar'],
			],
			[
				'case'   => 'Passed value is an array',
				'name'   => true,
				'search' => false,
				'result' => "(`test` IN ('foo','bar'))",
			],
		];

		return $data;
	}

	public static function getTestSearch()
	{
		$data[] = [
			[
				'mock'     => [
					'isEmpty' => true,
				],
				'value'    => '',
				'operator' => '=',
			],
			[
				'case'   => 'Value is empty',
				'result' => '',
			],
		];

		$data[] = [
			[
				'mock'     => [
					'isEmpty' => false,
				],
				'value'    => 'dummy',
				'operator' => '=',
			],
			[
				'case'   => 'Value is set',
				'result' => "(`test` = 'dummy')",
			],
		];

		$data[] = [
			[
				'mock'     => [
					'isEmpty' => false,
				],
				'value'    => 'dummy',
				'operator' => '!=',
			],
			[
				'case'   => 'Value is set and should be different',
				'result' => "NOT (`test` = 'dummy')",
			],
		];

		return $data;
	}

	public static function getTestGetFieldType()
	{
		$data[] = [
			[
				'type' => 'int (10)',
			],
			[
				'case'   => 'Field: int (10)',
				'result' => 'Number',
			],
		];

		$data[] = [
			[
				'type' => 'tinyint (10)',
			],
			[
				'case'   => 'Field: tinyint (10)',
				'result' => 'Boolean',
			],
		];

		$data[] = [
			[
				'type' => 'smallint (10)',
			],
			[
				'case'   => 'Field: smallint (10)',
				'result' => 'Boolean',
			],
		];

		$data[] = [
			[
				'type' => 'date',
			],
			[
				'case'   => 'Field: date',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'datetime',
			],
			[
				'case'   => 'Field: datetime',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'time',
			],
			[
				'case'   => 'Field: time',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'year',
			],
			[
				'case'   => 'Field: year',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'timestamp',
			],
			[
				'case'   => 'Field: timestamp',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'timestamp without time zone',
			],
			[
				'case'   => 'Field: timestamp without time zone',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'timestamp with time zone',
			],
			[
				'case'   => 'Field: timestamp with time zone',
				'result' => 'Date',
			],
		];

		$data[] = [
			[
				'type' => 'varchar(10)',
			],
			[
				'case'   => 'Field: varchar(10)',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'text',
			],
			[
				'case'   => 'Field: text',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'smalltext',
			],
			[
				'case'   => 'Field: smalltext',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'longtext',
			],
			[
				'case'   => 'Field: longtext',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'char(10)',
			],
			[
				'case'   => 'Field: char(10)',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'mediumtext',
			],
			[
				'case'   => 'Field: mediumtext',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'character varying(10)',
			],
			[
				'case'   => 'Field: character varying(10)',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'nvarchar(10)',
			],
			[
				'case'   => 'Field: nvarchar(10)',
				'result' => 'Text',
			],
		];

		$data[] = [
			[
				'type' => 'nchar(10)',
			],
			[
				'case'   => 'Field: nchar(10)',
				'result' => 'Text',
			],
		];

		return $data;
	}

	public static function getTestGetFieldException()
	{
		// Invalid type
		$data[] = [
			[
				'field' => null,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => 1,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => true,
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => 'asd',
			],
		];

		// Invalid type
		$data[] = [
			[
				'field' => [1],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'type' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name' => 'test',
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'field' => 'test',
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name'  => 'test',
					'field' => null,
				],
			],
		];

		// Missing fields
		$data[] = [
			[
				'field' => (object) [
					'name'  => null,
					'field' => 'test',
				],
			],
		];

		// Field ok, missing db object
		$data[] = [
			[
				'field' => (object) [
					'name'  => 'test',
					'field' => 'int(10)',
				],
			],
		];

		return $data;
	}
}
