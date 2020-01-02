<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\IP;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Stubs\Utils\IpStub;

require_once 'IpDataprovider.php';

/**
 * @covers  \FOF40\IP\IPHelper::<protected>
 * @covers  \FOF40\IP\IPHelper::<private>
 */
class IPHelperTest extends FOFTestCase
{
	/**
	 * @group            Ip
	 * @dataProvider     \FOF40\Tests\IP\IpDataprovider::getDetectAndCleanIP
	 */
	public function testDetectAndCleanIP($test, $check)
	{
		$msg = 'Ip::detectIP %s - Case: ' . $check['case'];

		$ip = new IpStub();

		$ip::$fakeIP = $test['fakeIP'];
		$ip::setUseFirstIpInChain($test['useFirst']);

		$result = $ip::detectAndCleanIP();

		$this->assertEquals($check['result'], $result, $msg);
	}
}

