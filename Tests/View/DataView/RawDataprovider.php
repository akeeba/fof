<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class RawDataprovider
{
	public static function getTest__construct()
	{
		$data[] = [
			[
				'mock' => [
					'isCli' => false,
				],
			],
			[
				'case'        => 'We are not in CLI',
				'permissions' => (object) [
					'create'    => false,
					'edit'      => false,
					'editown'   => false,
					'editstate' => false,
					'delete'    => false,
				],
			],
		];

		$data[] = [
			[
				'mock' => [
					'isCli' => true,
				],
			],
			[
				'case'        => 'We are in CLI',
				'permissions' => (object) [
					'create'    => true,
					'edit'      => true,
					'editown'   => true,
					'editstate' => true,
					'delete'    => true,
				],
			],
		];

		return $data;
	}
}
