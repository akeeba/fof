<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class BelongsToManyDataprovider
{
	public function getTestConstruct()
	{
		$data[] = [
			[
				'local'     => 'local',
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
			[
				'case'      => 'Passing all the info',
				'local'     => 'local',
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
		];

		$data[] = [
			[
				'local'     => 'local',
				'foreign'   => 'foreign',
				'pvTable'   => null,
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
			[
				'case'      => 'Passing all the info, except for the pivot table name',
				'local'     => 'local',
				'foreign'   => 'foreign',
				'pvTable'   => '#__fakeapp_parts_groups',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
		];

		$data[] = [
			[
				'local'     => 'local',
				'foreign'   => null,
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
			[
				'case'      => 'Passing all the info, except for foreign key',
				'local'     => 'local',
				'foreign'   => 'fakeapp_part_id',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
		];

		$data[] = [
			[
				'local'     => 'local',
				'foreign'   => null,
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => null,
			],
			[
				'case'      => 'Passing all the info, except for foreign and pivot foreign keys',
				'local'     => 'local',
				'foreign'   => 'fakeapp_part_id',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'fakeapp_part_id',
			],
		];

		$data[] = [
			[
				'local'     => null,
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
			[
				'case'      => 'Passing all the info, except for local key',
				'local'     => 'fakeapp_group_id',
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'pvLocal',
				'pvForeign' => 'pvForeign',
			],
		];

		$data[] = [
			[
				'local'     => null,
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => null,
				'pvForeign' => 'pvForeign',
			],
			[
				'case'      => 'Passing all the info, except for local and pivot local keys',
				'local'     => 'fakeapp_group_id',
				'foreign'   => 'foreign',
				'pvTable'   => 'pvTable',
				'pvLocal'   => 'fakeapp_group_id',
				'pvForeign' => 'pvForeign',
			],
		];

		return $data;
	}

	public function getTestSetDataFromCollection()
	{
		$data[] = [
			[
				'keymap' => null,
			],
			[
				'case'  => 'Keymap is not an array',
				'count' => 0,
			],
		];

		$data[] = [
			[
				'keymap' => [],
			],
			[
				'case'  => "Keymap is an array but it doesn't contain any correct value",
				'count' => 0,
			],
		];

		$data[] = [
			[
				'keymap' => [
					1 => [1, 2, 3],
					2 => [1, 2],
				],
			],
			[
				'case'  => "Keymap is an array and it's correct",
				'count' => 2,
			],
		];

		return $data;
	}

	public function getTestSaveAll()
	{
		$data[] = [
			[

			],
			[
				'case' => '',
			],
		];

		return $data;
	}
}
