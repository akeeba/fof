<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Download\Adapter;

class FopenDataprovider
{
	public static function getTestDownloadAndReturn()
	{
		return [
			[
				'setup' => [
					'httpstatus' => 200,
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 0,
					'to'        => 0,
					'retSize'   => 1048576,
					'exception' => false,
					'message'   => 'Download a simple 1M file',
				],
			],

			[
				'setup' => [
				],
				'test'  => [
					'url'       => 'http://www.example.com/IDoNotExist.dat',
					'from'      => 0,
					'to'        => 0,
					'retSize'   => 0,
					'exception' => [
						'name'    => 'Exception',
						'message' => 'LIB_FOF40_DOWNLOAD_ERR_FOPEN_ERROR',
						'code'    => '404',
					],
					'message'   => '404 on non-existent file results in Exception',
				],
			],

			[
				'setup' => [
					'httpstatus' => 403,
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 0,
					'to'        => 0,
					'retSize'   => 0,
					'exception' => [
						'name'    => 'Exception',
						'message' => 'LIB_FOF40_DOWNLOAD_ERR_HTTPERROR',
						'code'    => '403',
					],
					'message'   => '403 Forbidden results in Exception',
				],
			],

			[
				'setup' => [
					'returnSize' => 2 * 1048576,
					'httpstatus' => 200,
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 0,
					'to'        => 1048575,
					'retSize'   => 1048576,
					'exception' => false,
					'message'   => 'First 1M chunk of a 2M file',
				],
			],

			[
				'setup' => [
					'returnSize' => 2 * 1048576,
					'httpstatus' => 200,
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 1048576,
					'to'        => 2 * 1048576 - 1,
					'retSize'   => 1048576,
					'exception' => false,
					'message'   => 'Last 1M chunk of a 2M file',
				],
			],

			[
				'setup' => [
					'returnSize' => 2 * 1048576,
					'httpstatus' => 200,
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 2 * 1048576 - 1,
					'to'        => 1048576,
					'retSize'   => 1048576,
					'exception' => false,
					'message'   => 'Last 1M chunk of a 2M file, accidentally inverted to/from',
				],
			],
		];
	}
}
