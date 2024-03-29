<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class AssetsDataprovider
{
	public static function getTestOnAfterSave()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'load'    => 0,
				'track'   => false,
				'rules'   => null,
			],
			[
				'case'  => 'Without asset support',
				'count' => 0,
				'rules' => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 0,
				'track'   => false,
				'rules'   => null,
			],
			[
				'case'  => 'With asset support but no asset tracking',
				'count' => 0,
				'rules' => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 2,
				'track'   => true,
				'rules'   => null,
			],
			[
				'case'  => 'Asset support but no rules passed',
				'count' => 1,
				'rules' => '{}',
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'load'    => 2,
				'track'   => true,
				'rules'   => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}',
			],
			[
				'case'  => 'Asset support with rules',
				'count' => 1,
				'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}',
			],
		];

		// Asset support with rules - table with aliases
		/*$data[] = array(
			array(
				'name' => 'foobaraliases',
			),
			array(
				'id'      => 2,
				'tbl_key' => 'id_foobar_aliases',
				'alias'   => array(
					'asset_id' => 'fo_asset_id',
				),
				'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
			),
			array('return' => true, 'count' => 1, 'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}')
		);*/

		return $data;
	}

	public static function getTestOnAfterBind()
	{
		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'bind'    => '',
				'load'    => 0,
				'track'   => false,
			],
			[
				'case'  => 'Without asset tracking',
				'rules' => '',
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'bind'    => '',
				'load'    => 0,
				'track'   => true,
			],
			[
				'case'  => 'With asset tracking, no rules',
				'rules' => '',
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'bind'    => [
					'rules' => [
						'core.delete'     => [
							1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 => '', 8 => '',
						],
						'core.edit'       => [
							1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => 1, 5 => '', 8 => '',
						],
						'core.edit.state' => [
							1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => '', 4 => '', 5 => 1, 8 => '',
						],
						'core.edit.own'   => [
							1 => '', 9 => '', 6 => 1, 7 => '', 2 => '', 3 => 1, 4 => '', 5 => '', 8 => '',
						],
					],
				],
				'load'    => 0,
				'track'   => true,
			],
			[
				'case'  => 'With asset tracking, rules with empty values',
				'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}',
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'bind'    => [
					'rules' => [
						'core.delete'     => [6 => 1],
						'core.edit'       => [6 => 1, 4 => 1],
						'core.edit.state' => [6 => 1, 5 => 1],
						'core.edit.own'   => [6 => 1, 3 => 1],
					],
				],
				'load'    => 0,
				'track'   => true,
			],
			[
				'case'  => 'With asset tracking, rules without empty values',
				'rules' => '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}',
			],
		];

		return $data;
	}

	public static function getTestOnBeforeDelete()
	{
		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'load'    => 0,
				'track'   => false,
				'id'      => null,
			],
			[
				'case'      => 'Without asset tracking',
				'count'     => 0,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'load'    => 0,
				'track'   => true,
				'id'      => null,
			],
			[
				'case'      => 'With asset tracking, not loaded',
				'count'     => 0,
				'exception' => true,
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'load'    => 2,
				'track'   => true,
				'id'      => null,
			],
			[
				'case'      => 'With asset tracking, loaded no asset',
				'count'     => 0,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'load'    => 4,
				'track'   => true,
				'id'      => null,
			],
			[
				'case'      => 'With asset tracking, loaded with asset',
				'count'     => 1,
				'exception' => false,
			],
		];

		$data[] = [
			[
				'table'   => '#__foftest_foobars',
				'tableid' => 'foftest_foobar_id',
				'load'    => 0,
				'track'   => true,
				'id'      => 4,
			],
			[
				'case'      => 'With asset tracking, loaded (using ID) with asset',
				'count'     => 1,
				'exception' => false,
			],
		];

		return $data;
	}
}
