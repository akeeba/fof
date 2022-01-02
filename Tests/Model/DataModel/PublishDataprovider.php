<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class PublishDataprovider
{
	public static function getTestArchive()
	{
		$data[] = [
			[
				'mock'    => [
					'before' => '',
					'after'  => '',
					'alias'  => [],
				],
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Table with no enabled field',
				'dispatcher' => 0,
				'save'       => false,
				'exception'  => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => '',
					'after'  => '',
					'alias'  => [
						'enabled' => 'fo_enabled',
					],
				],
				'tableid' => 'id_foobar_aliases',
				'table'   => '#__foftest_foobaraliases',
			],
			[
				'case'       => 'Table with enabled field (alias)',
				'dispatcher' => 2,
				'save'       => true,
				'exception'  => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => '',
					'after'  => '',
					'alias'  => [],
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with enabled field',
				'dispatcher' => 2,
				'save'       => true,
				'exception'  => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => function () {
						return false;
					},
					'after'  => '',
					'alias'  => [],
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with enabled field, onBefore returns false',
				'dispatcher' => 2,
				'save'       => true,
				'exception'  => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => function () {
						return true;
					},
					'after'  => function () {
						return false;
					},
					'alias'  => [],
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with enabled field, onAfter returns false',
				'dispatcher' => 2,
				'save'       => true,
				'exception'  => false,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => function () {
						throw new \Exception();
					},
					'after'  => function () {
						return false;
					},
					'alias'  => [],
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with enabled field, onBefore throws an exception',
				'dispatcher' => 0,
				'save'       => false,
				'exception'  => true,
			],
		];

		$data[] = [
			[
				'mock'    => [
					'before' => function () {
						return true;
					},
					'after'  => function () {
						throw new \Exception();
					},
					'alias'  => [],
				],
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with enabled field, onAfter throws an exception',
				'dispatcher' => 1,
				'save'       => true,
				'exception'  => true,
			],
		];

		return $data;
	}

	public static function getTestTrash()
	{
		$data[] = [
			[
				'id' => null,
			],
			[
				'case'       => 'Table with publish support, already loaded',
				'before'     => 1,
				'after'      => 1,
				'find'       => false,
				'dispatcher' => 2,
				'enabled'    => -2,
			],
		];

		$data[] = [
			[
				'id' => 1,
			],
			[
				'case'       => 'Table with publish support, not loaded',
				'before'     => 1,
				'after'      => 1,
				'find'       => true,
				'dispatcher' => 2,
				'enabled'    => -2,
			],
		];

		return $data;
	}

	public static function getTestTrashException()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'id'      => 1,
			],
			[
				'case'      => 'Table with no publish support',
				'exception' => 'FOF40\\Model\\DataModel\\Exception\\SpecialColumnMissing',
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'id'      => null,
			],
			[
				'case'      => 'Table not loaded',
				'exception' => 'FOF40\Model\DataModel\Exception\RecordNotLoaded',
			],
		];

		return $data;
	}

	public static function getTestPublish()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'state'   => 1,
			],
			[
				'case'       => 'Table with no publish support',
				'dispatcher' => 0,
				'before'     => 0,
				'after'      => 0,
				'enabled'    => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'state'   => 1,
			],
			[
				'case'       => 'Table with publish support (record enabling)',
				'dispatcher' => 2,
				'before'     => 1,
				'after'      => 1,
				'enabled'    => 1,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'state'   => 0,
			],
			[
				'case'       => 'Table with publish support (record disabling)',
				'dispatcher' => 2,
				'before'     => 1,
				'after'      => 1,
				'enabled'    => 0,
			],
		];

		return $data;
	}

	public static function getTestRestore()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
				'id'      => '',
			],
			[
				'case'       => 'Table with no publish support',
				'before'     => 0,
				'after'      => 0,
				'find'       => false,
				'dispatcher' => 0,
				'enabled'    => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'id'      => null,
			],
			[
				'case'       => 'Table with publish support, already loaded',
				'before'     => 1,
				'after'      => 1,
				'find'       => false,
				'dispatcher' => 2,
				'enabled'    => 0,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
				'id'      => 1,
			],
			[
				'case'       => 'Table with publish support, not loaded',
				'before'     => 1,
				'after'      => 1,
				'find'       => true,
				'dispatcher' => 2,
				'enabled'    => 0,
			],
		];

		return $data;
	}

	public static function getTestUnpublish()
	{
		$data[] = [
			[
				'tableid' => 'foftest_bare_id',
				'table'   => '#__foftest_bares',
			],
			[
				'case'       => 'Table with no publish support',
				'before'     => 0,
				'after'      => 0,
				'dispatcher' => 0,
				'enabled'    => null,
			],
		];

		$data[] = [
			[
				'tableid' => 'foftest_foobar_id',
				'table'   => '#__foftest_foobars',
			],
			[
				'case'       => 'Table with publish support',
				'before'     => 1,
				'after'      => 1,
				'dispatcher' => 2,
				'enabled'    => 0,
			],
		];

		return $data;
	}
}
