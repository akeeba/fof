<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class TagsDataprovider
{
	public static function getTestOnAfterSave()
	{
		$data[] = [
			'input' => [
				'tags'  => null,
				'input' => [
					'task' => 'publish',
				],
				'mock'  => [
					'postProc' => true,
				],
			],
			'check' => [
				'case'         => 'Task we are not interested into',
				'exception'    => false,
				'contentType'  => 0,
				'checkContent' => false,
			],
		];

		$data[] = [
			'input' => [
				'tags'  => null,
				'input' => [
					'task' => 'apply',
				],
				'mock'  => [
					'postProc' => true,
				],
			],
			'check' => [
				'case'         => 'Apply task',
				'exception'    => false,
				'contentType'  => 2,
				'checkContent' => true,
			],
		];

		$data[] = [
			'input' => [
				'tags'  => null,
				'input' => [
					'task' => 'save',
				],
				'mock'  => [
					'postProc' => true,
				],
			],
			'check' => [
				'case'         => 'Save task',
				'exception'    => false,
				'contentType'  => 2,
				'checkContent' => true,
			],
		];

		$data[] = [
			'input' => [
				'tags'  => null,
				'input' => [
					'task' => 'savenew',
				],
				'mock'  => [
					'postProc' => true,
				],
			],
			'check' => [
				'case'         => 'Savenew task',
				'exception'    => false,
				'contentType'  => 2,
				'checkContent' => true,
			],
		];

		$data[] = [
			'input' => [
				'tags'  => [99],
				'input' => [
					'task' => 'savenew',
				],
				'mock'  => [
					'postProc' => false,
				],
			],
			'check' => [
				'case'         => "Tags didn't change",
				'exception'    => false,
				'contentType'  => 1,
				'checkContent' => false,
			],
		];

		$data[] = [
			'input' => [
				'tags'  => null,
				'input' => [
					'task' => 'savenew',
				],
				'mock'  => [
					'postProc' => false,
				],
			],
			'check' => [
				'case'         => 'An error occurs in post processing',
				'exception'    => true,
				'contentType'  => 2,
				'checkContent' => true,
			],
		];

		return $data;
	}

	public static function getTestOnAfterDelete()
	{
		$data[] = [
			'input' => [
				'mock' => [
					'delete' => true,
				],
			],
			'check' => [
				'exception' => false,
			],
		];

		$data[] = [
			'input' => [
				'mock' => [
					'delete' => false,
				],
			],
			'check' => [
				'exception' => true,
			],
		];

		return $data;
	}

	public static function getTestOnAfterBind()
	{
		$data[] = [
			'input' => [
				'load' => 0,
				'tags' => '123',
			],
			'check' => [
				'case'        => 'Tag field already loaded',
				'contentType' => false,
				'addKnown'    => false,
				'result'      => '123',
			],
		];

		$data[] = [
			'input' => [
				'load' => 1,
				'tags' => null,
			],
			'check' => [
				'case'        => 'Tag field empty - record loaded',
				'contentType' => true,
				'addKnown'    => true,
				'result'      => 99,
			],
		];

		$data[] = [
			'input' => [
				'load' => 0,
				'tags' => null,
			],
			'check' => [
				'case'        => 'Tag field empty - record not loaded',
				'contentType' => true,
				'addKnown'    => true,
				'result'      => 99,
			],
		];

		return $data;
	}
}
