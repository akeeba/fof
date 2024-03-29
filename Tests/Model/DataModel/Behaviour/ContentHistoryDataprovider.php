<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class ContentHistoryDataprovider
{
	public static function getTestOnAfterSave()
	{
		$data[] = [
			'input' => [
				'save_history' => 1,
			],
			'check' => [
				'case'  => 'Component enables history saving',
				'store' => 1,
			],
		];

		$data[] = [
			'input' => [
				'save_history' => 0,
			],
			'check' => [
				'case'  => 'Component does not enable history saving',
				'store' => 0,
			],
		];

		return $data;
	}

	public static function getTestOnBeforeDelete()
	{
		$data[] = [
			'input' => [
				'save_history' => 1,
			],
			'check' => [
				'case'   => 'Component enables history saving',
				'delete' => 1,
			],
		];

		$data[] = [
			'input' => [
				'save_history' => 0,
			],
			'check' => [
				'case'   => 'Component does not enable history saving',
				'delete' => 0,
			],
		];

		return $data;
	}
}
