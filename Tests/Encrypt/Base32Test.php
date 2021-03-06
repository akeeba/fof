<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace FOF30\Tests\Encrypt;


use FOF30\Encrypt\Base32;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

/**
 * @coversDefaultClass FOF30\Encrypt\Base32
 *
 * @package FOF30\Tests\Encrypt
 */
class Base32Test extends FOFTestCase
{
	/** @var  Base32 */
	protected $base32;

	/**
	 * @return  void
	 */
	protected function setUp()
	{

		$this->base32 = new Base32();
	}

	/**
	 *
	 * @return  void
	 */
	public function testEncode()
	{
		$this->assertEquals('MFRGGZDFMZTWQ2LLNRWW433QOFZHG5DVOZ3XQ6L2GEZDGNBVGY3TQOJQIFBEGRCFIZDUQSKLJRGU4T2QKFJFGVCVKZLVQWK2FIRS2LRMEERMFJZEEUTC6KBJHU7UAQCALQVA',
			$this->base32->encode('abcdefghiklmnopqrstuvwxyz1234567890ABCDEFGHIKLMNOPQRSTUVWXYZ*#-.,!"§$%&/()=?@@@\*')
		);
	}

	/**
	 * [testGetVar description]
	 *
	 * @return  void
	 */
	public function testDecode()
	{
		$this->assertEquals('abcdefghiklmnopqrstuvwxyz1234567890ABCDEFGHIKLMNOPQRSTUVWXYZ*#-.,!"§$%&/()=?@@@\*',
			$this->base32->decode('MFRGGZDFMZTWQ2LLNRWW433QOFZHG5DVOZ3XQ6L2GEZDGNBVGY3TQOJQIFBEGRCFIZDUQSKLJRGU4T2QKFJFGVCVKZLVQWK2FIRS2LRMEERMFJZEEUTC6KBJHU7UAQCALQVA')
		);
	}

	public function testDecodeWithCrapData()
	{
		$this->setExpectedException('\Exception');
		$this->base32->decode('Crap data');
	}

	/**
	 * @dataProvider getTestBin2StrExceptions
	 */
	public function testBin2StrExceptions($crapData, $message)
	{
		$this->setExpectedException('\Exception');
		ReflectionHelper::invoke($this->base32, 'bin2str', $crapData);
	}

	/**
	 * @dataProvider getTestBin2StrExceptions
	 */
	public function testFromBinExceptions($crapData, $message)
	{
		$this->setExpectedException('\Exception');
		ReflectionHelper::invoke($this->base32, 'fromBin', $crapData);
	}

	public function getTestBin2StrExceptions()
	{
		return array(
			array('101010101', 'Not divisable by 8'),
			array('0A0A0A0A', 'Not binary data'),
		);
	}
}
