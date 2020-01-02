<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class HasManyDataprovider
{
	public static function getTestConstruct()
	{
		$data[] = [
			[
				'local'   => null,
				'foreign' => null,
			],
			[
				'case'    => 'Local and foreign keys not supplied',
				'local'   => 'fakeapp_parent_id',
				'foreign' => 'fakeapp_parent_id',
			],
		];

		$data[] = [
			[
				'local'   => 'local',
				'foreign' => null,
			],
			[
				'case'    => 'Local key supplied',
				'local'   => 'local',
				'foreign' => 'local',
			],
		];

		$data[] = [
			[
				'local'   => null,
				'foreign' => 'foreign',
			],
			[
				'case'    => 'Foreign key supplied',
				'local'   => 'fakeapp_parent_id',
				'foreign' => 'foreign',
			],
		];

		$data[] = [
			[
				'local'   => 'local',
				'foreign' => 'foreign',
			],
			[
				'case'    => 'Local and foreign keys supplied',
				'local'   => 'local',
				'foreign' => 'foreign',
			],
		];

		return $data;
	}
}
