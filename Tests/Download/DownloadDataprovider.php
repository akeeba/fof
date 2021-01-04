<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Download;

class DownloadDataprovider
{
	public static function getTestSetAdapter()
	{
		return [
			['Fopen', true],
			['FOPEN', true],
			['fopen', true],
			['\\FOF40\\Download\\Adapter\\Fopen', true],
			['Curl', false],
			['CURL', false],
			['curl', false],
			['\\FOF40\\Download\\Adapter\\Curl', false],
			['Spike', false],
			['\\JRegistry', false],
			[null, false],
		];
	}

	public static function getTestGetAdapterName()
	{
		return [
			['Fopen', 'fopen'],
			['FOPEN', 'fopen'],
			['fopen', 'fopen'],
			['Curl', 'curl'],
			['CURL', 'curl'],
			['curl', 'curl'],
			['\\FOF40\\Download\\Adapter\\Fopen', 'fopen'],
			['\\FOF40\\Download\\Adapter\\Curl', 'curl'],
			['Spike', 'curl'],
			['\\JRegistry', 'curl'],
			[null, 'curl'],
		];
	}

	public static function getTestGetFromUrl()
	{
		return [
			[
				'setup' => [
					'httpstatus' => 200,
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'from'    => 0,
					'to'      => 0,
					'retSize' => 1048576,
					'false'   => false,
					'message' => 'Download a simple 1M file',
				],
			],

			[
				'setup' => [],
				'test'  => [
					'url'     => 'http://www.example.com/IDoNotExist.dat',
					'from'    => 0,
					'to'      => 0,
					'retSize' => 0,
					'false'   => true,
					'message' => '404 on non-existent file results in Exception',
				],
			],

			[
				'setup' => [
					'httpstatus' => 403,
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'from'    => 0,
					'to'      => 0,
					'retSize' => 0,
					'false'   => true,
					'message' => '403 Forbidden results in Exception',
				],
			],

			[
				'setup' => [
					'errno' => 999,
					'error' => 'Foobar',
				],
				'test'  => [
					'url'     => 'http://www.example.com/donwload.dat',
					'from'    => 0,
					'to'      => 0,
					'retSize' => 0,
					'false'   => true,
					'message' => '403 Forbidden',
				],
			],
		];
	}

	public static function getTestImportFromUrl()
	{
		return [
			[
				'setup'  => [
					'httpstatus' => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 1048576,
					'loop'    => false,
					'expect'  => [
						'status'    => true,
						'error'     => '',
						'frag'      => -1, // Done file
						'totalSize' => 1048576,
						'doneSize'  => 1048576,
						'percent'   => 100,
					],
					'message' => 'Download a simple 1M file',
				],
			],

			[
				'setup'  => [
					'httpstatus' => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize'   => 1048576,
					'loop'      => false,
					'localfile' => null,
					'expect'    => [
						'status'    => true,
						'error'     => '',
						'frag'      => -1, // Done file
						'totalSize' => 1048576,
						'doneSize'  => 1048576,
						'percent'   => 100,
					],
					'message'   => 'Download a simple 1M file without specifying a local file',
				],
			],

			[
				'setup'  => [
					'returnSize'   => 5242880,
					'reportedSize' => 5242880,
					'httpstatus'   => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 5242880,
					'loop'    => true,
					'expect'  => [
						'status'    => true,
						'error'     => '',
						'frag'      => -1, // Done file
						'totalSize' => 5242880,
						'doneSize'  => 5242880,
						'percent'   => 100,
					],
					'message' => 'Fully download a staggered 5M file',
				],
			],

			[
				'setup'  => [
					'returnSize'   => 5242880,
					'reportedSize' => 5242880,
					'httpstatus'   => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 1048576,
					'loop'    => false,
					'expect'  => [
						'status'    => true,
						'error'     => '',
						'frag'      => 1,
						'totalSize' => 5242880,
						'doneSize'  => 1048576,
						'percent'   => 20,
					],
					'message' => 'Download the first 1M chunk of a staggered 5M file',
				],
			],


			[
				'setup'  => [
					'returnSize'   => 5242880,
					'reportedSize' => -1,
					'httpstatus'   => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 1048576,
					'loop'    => false,
					'expect'  => [
						'status'    => true,
						'error'     => '',
						'frag'      => 1,
						'totalSize' => 0,
						'doneSize'  => 1048576,
						'percent'   => 0,
					],
					'message' => 'Download the first 1M chunk of a staggered 5M file which does not return its size',
				],
			],

			[
				'setup'  => [
					'returnSize'   => 5242880,
					'reportedSize' => -1,
					'httpstatus'   => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 5242880,
					'loop'    => true,
					'expect'  => [
						'status'    => true,
						'error'     => '',
						'frag'      => -1, // Done file
						'totalSize' => 5242880,
						'doneSize'  => 5242880,
						'percent'   => 100,
					],
					'message' => 'Fully download a staggered 5M file which doesn\'t return its size',
				],
			],

			[
				'setup'  => [
					'httpstatus' => 200,
				],
				'params' => [
					'url'    => 'http://www.example.com/donwload.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize'   => 1048576,
					'loop'      => false,
					'localfile' => '/foo/bar/baz.dat',
					'expect'    => [
						'status' => false,
						'error'  => 'LIB_FOF40_DOWNLOAD_ERR_COULDNOTWRITELOCALFILE',
					],
					'message'   => 'Unwritable local file leads to error',
				],
			],

			[
				'setup'  => [],
				'params' => [
					'url'    => 'http://www.example.com/IDoNotExist.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 0,
					'loop'    => false,
					'expect'  => [
						'status' => false,
						'error'  => 'LIB_FOF40_DOWNLOAD_ERR_HTTPERROR',
					],
					'message' => 'HTTP 404 results in error',
				],
			],

			[
				'setup'  => [
					'httpstatus' => 403,
				],
				'params' => [
					'url'    => 'http://www.example.com/IDoNotExist.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 0,
					'loop'    => false,
					'expect'  => [
						'status' => false,
						'error'  => 'LIB_FOF40_DOWNLOAD_ERR_HTTPERROR',
					],
					'message' => 'HTTP 403 results in error',
				],
			],

			[
				'setup'  => [
					'errno' => 999,
					'error' => 'Foobar',
				],
				'params' => [
					'url'    => 'http://www.example.com/IDoNotExist.dat',
					'length' => 1048576,
				],
				'test'   => [
					'retSize' => 0,
					'loop'    => false,
					'expect'  => [
						'status' => false,
						'error'  => 'LIB_FOF40_DOWNLOAD_ERR_CURL_ERROR',
					],
					'message' => 'cURL error results in error returned',
				],
			],
		];
	}
}
