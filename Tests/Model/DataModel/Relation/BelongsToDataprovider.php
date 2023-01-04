<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class BelongsToDataprovider
{
	public static function getTestConstruct()
	{
		$data[] = [
			[
				'local'   => null,
				'foreign' => null,
			],
			[
				'case'    => 'Local and foreign keys not passed',
				'local'   => 'fakeapp_parent_id',
				'foreign' => 'fakeapp_parent_id',
			],
		];

		$data[] = [
			[
				'local'   => 'local',
				'foreign' => 'foreign',
			],
			[
				'case'    => 'Local and foreign keys passed',
				'local'   => 'local',
				'foreign' => 'foreign',
			],
		];

		$data[] = [
			[
				'local'   => 'local',
				'foreign' => null,
			],
			[
				'case'    => 'Local key passed, foreign key not passed',
				'local'   => 'local',
				'foreign' => 'fakeapp_parent_id',
			],
		];

		$data[] = [
			[
				'local'   => null,
				'foreign' => 'foreign',
			],
			[
				'case'    => 'Local key not passed, foreign key passed',
				'local'   => 'fakeapp_parent_id',
				'foreign' => 'foreign',
			],
		];

		return $data;
	}
}
