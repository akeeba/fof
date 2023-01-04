<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Tests\IP;

class IpDataprovider
{
	public static function getDetectAndCleanIP()
	{
		$data['Single IPv4, using the first one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Single IPv4, using the first one',
				'result' => '127.0.0.1',
			],
		];

		$data['Single IPv6, using the first one'] = [
			// test
			[
				'fakeIP'   => '2607:f0d0:1002:0051:0000:0000:0000:0004',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Single IPv6, using the first one',
				'result' => '2607:F0D0:1002:0051:0000:0000:0000:0004',
			],
		];

		$data['IPv4 and IPv6, using the first one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,2607:f0d0:1002:0051:0000:0000:0000:0004',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 and IPv6, using the first one',
				'result' => '127.0.0.1',
			],
		];

		$data['IPv4 and IPv6, using the last one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,2607:f0d0:1002:0051:0000:0000:0000:0004',
				'useFirst' => false,
			],
			// check
			[
				'case'   => 'IPv4 and IPv6, using the last one',
				'result' => '2607:F0D0:1002:0051:0000:0000:0000:0004',
			],
		];

		$data['IPv6 through proxy (SHOULD NEVER HAPPEN)'] = [
			// test
			[
				'fakeIP'   => 'dead:beef:bad0:0bad:0000:0000:0000:0001,2607:f0d0:1002:0051:0000:0000:0000:0004',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 and IPv6, using the last one',
				'result' => 'DEAD:BEEF:BAD0:0BAD:0000:0000:0000:0001',
			],
		];

		$data['Two IPv4, using the first one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,1.1.1.1',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Two IPv4, using the first one',
				'result' => '127.0.0.1',
			],
		];

		$data['Two IPv4, using the last one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,1.1.1.1',
				'useFirst' => false,
			],
			// check
			[
				'case'   => 'Two IPv4, using the last one',
				'result' => '1.1.1.1',
			],
		];

		$data['Three IPv4, using the first one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,1.1.1.1,2.2.2.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Three IPv4, using the first one',
				'result' => '127.0.0.1',
			],
		];

		$data['Three IPv4, using the last one'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,1.1.1.1,2.2.2.2',
				'useFirst' => false,
			],
			// check
			[
				'case'   => 'Three IPv4, using the last one',
				'result' => '2.2.2.2',
			],
		];

		$data['Malformed IPs (1)'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1, 1.1.1.1, 2.2.2.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Malformed IPs (1)',
				'result' => '127.0.0.1',
			],
		];

		$data['Malformed IPs (2)'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1,  1.1.1.1,  2.2.2.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Malformed IPs (2)',
				'result' => '127.0.0.1',
			],
		];

		$data['Malformed IPs (3)'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1 1.1.1.1 2.2.2.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Malformed IPs (3)',
				'result' => '127.0.0.1',
			],
		];

		$data['Malformed IPs (4)'] = [
			// test
			[
				'fakeIP'   => '127.0.0.1  1.1.1.1  2.2.2.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'Malformed IPs (4)',
				'result' => '127.0.0.1',
			],
		];

		$data['IPv4 wrapped in IPv6, compressed zeroes'] = [
			// test
			[
				'fakeIP'   => '::ffff:192.168.1.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 wrapped in IPv6, compressed zeroes',
				'result' => '192.168.1.2',
			],
		];

		$data['IPv4 wrapped in IPv6, expanded zeroes'] = [
			// test
			[
				'fakeIP'   => '0:0:0:0:0:ffff:192.168.1.2',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 wrapped in IPv6, expanded zeroes',
				'result' => '192.168.1.2',
			],
		];

		$data['IPv4 wrapped in IPv6, all hex, collapsed zeroes'] = [
			// test
			[
				'fakeIP'   => '::FFFF:C0A8:0101',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 wrapped in IPv6, all hex, collapsed zeroes',
				'result' => '192.168.1.1',
			],
		];

		$data['IPv4 wrapped in IPv6, all hex, expanded zeroes'] = [
			// test
			[
				'fakeIP'   => '0:0:0:0:0:FFFF:C0A8:0101',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 wrapped in IPv6, all hex, expanded zeroes',
				'result' => '192.168.1.1',
			],
		];

		$data['IPv4 wrapped in IPv6, through proxy, IPv4 returned'] = [
			// test
			[
				'fakeIP'   => '0:0:0:0:0:FFFF:C0A8:0101,2607:f0d0:1002:0051:0000:0000:0000:0004',
				'useFirst' => true,
			],
			// check
			[
				'case'   => 'IPv4 wrapped in IPv6, through proxy, IPv4 returned',
				'result' => '192.168.1.1',
			],
		];

		return $data;
	}
}