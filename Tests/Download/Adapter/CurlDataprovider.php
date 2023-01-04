<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Download\Adapter;

class CurlDataprovider
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
						'message' => 'LIB_FOF40_DOWNLOAD_ERR_HTTPERROR',
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
					'errno' => 999,
					'error' => 'Foobar',
				],
				'test'  => [
					'url'       => 'http://www.example.com/donwload.dat',
					'from'      => 0,
					'to'        => 0,
					'retSize'   => 0,
					'exception' => [
						'name'    => 'Exception',
						'message' => 'LIB_FOF40_DOWNLOAD_ERR_CURL_ERROR',
						'code'    => 999,
					],
					'message'   => '403 Forbidden',
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

	public static function getTestGetFileSize()
	{
		return [
			[
				'setup' => [
					'httpstatus' => 200,
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'retSize' => 1048576,
					'message' => 'Simple 1M file',
				],
			],

			[
				'setup' => [
				],
				'test'  => [
					'url'     => 'http://www.example.com/IDoNotExist.dat',
					'retSize' => -1,
					'message' => '404 on non-existent file results in -1 size',
				],
			],

			[
				'setup' => [
					'httpstatus' => 403,
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'retSize' => -1,
					'message' => '403 Forbidden results in -1 size',
				],
			],

			[
				'setup' => [
					'errno' => 999,
					'error' => 'Foobar',
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'retSize' => -1,
					'message' => '403 Forbidden results in -1 size',
				],
			],

			[
				'setup' => [
					'returnSize'   => 2 * 1048576,
					'reportedSize' => 2 * 1048576,
					'httpstatus'   => 200,
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'retSize' => 2 * 1048576,
					'message' => 'A 2M file',
				],
			],
		];
	}

}
