<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class CreatedDataprovider
{
	public static function getTestOnBeforeCreate()
	{
		$data[] = [
			'input' => [
				'tableid'    => 'foftest_foobar_id',
				'table'      => '#__foftest_foobars',
				'created_on' => null,
				'created_by' => null,
				'aliases'    => [],
			],
			'check' => [
				'case'       => 'Datetime and user fields empty',
				'created_on' => true,
				'created_by' => 99,
			],
		];

		$data[] = [
			'input' => [
				'tableid'    => 'foftest_foobar_id',
				'table'      => '#__foftest_foobars',
				'created_on' => '0000-00-00 00:00:00',
				'created_by' => null,
				'aliases'    => [],
			],
			'check' => [
				'case'       => 'Datetime is an empty timestamp (all zeros)',
				'created_on' => true,
				'created_by' => 99,
			],
		];

		$data[] = [
			'input' => [
				'tableid'    => 'foftest_foobar_id',
				'table'      => '#__foftest_foobars',
				'created_on' => '2015-07-13 15:09:00',
				'created_by' => 88,
				'aliases'    => [],
			],
			'check' => [
				'case'       => 'Datetime and user are not empty',
				'created_on' => false,
				'created_by' => false,
			],
		];

		$data[] = [
			'input' => [
				'tableid'    => 'id_foobar_aliases',
				'table'      => '#__foftest_foobaraliases',
				'created_on' => null,
				'created_by' => null,
				'aliases'    => [
					'created_by' => 'fo_created_by',
					'created_on' => 'fo_created_on',
				],
			],
			'check' => [
				'case'       => 'No datetime and user on table with aliases',
				'created_on' => true,
				'created_by' => 99,
			],
		];

		$data[] = [
			'input' => [
				'tableid'    => 'foftest_bare_id',
				'table'      => '#__foftest_bares',
				'created_on' => null,
				'created_by' => null,
				'aliases'    => [],
			],
			'check' => [
				'case'       => 'Table with no created_on/by support',
				'created_on' => false,
				'created_by' => false,
			],
		];

		return $data;
	}
}
