<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


namespace FOF40\Tests\Encrypt;


use FOF40\Encrypt\Aes;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Stubs\Encrypt\MockPhpfunc;

/**
 * @coversDefaultClass \FOF40\Encrypt\Aes
 *
 * @package            FOF40\Tests\Encrypt
 */
class AesTest extends FOFTestCase
{
	/** @var  Aes */
	protected $aes;

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		$this->aes = new Aes();
		$this->aes->setPassword('x123456789012345678901234567890x');
	}

	/**
	 * @covers \FOF40\Encrypt\Aes::IsSupported
	 *
	 * @return  void
	 */
	public function testIsSupported_OpenSSL()
	{
		$functions_enabled = [
			'openssl_get_cipher_methods',
			'openssl_random_pseudo_bytes',
			'openssl_cipher_iv_length',
			'openssl_encrypt',
			'openssl_decrypt',
			'hash',
			'hash_algos',
			'base64_encode',
			'base64_decode',
		];

		$algorithms = [
			'aes-128-cbc',
		];

		$hashAlgos = [
			'sha256',
		];

		// Create a mock php function with all prerequisites met
		$phpfunc = new MockPhpfunc();
		$phpfunc->setFunctions($functions_enabled);
		$phpfunc->setOpenSSLAlgorithms($algorithms);
		$phpfunc->setHashAlgorithms($hashAlgos);

		// Just for code coverage
		$this->assertNotNull(Aes::isSupported());

		// All prerequisites met = supported
		$this->assertTrue(Aes::isSupported($phpfunc), 'All prerequisites met = supported');

		// No hash algorithms = not supported
		$phpfunc->setHashAlgorithms([]);
		$this->assertFalse(Aes::isSupported($phpfunc), 'No hash algorithms = not supported');
		$phpfunc->setHashAlgorithms($hashAlgos);

		// No OpenSSL algorithms = not supported
		$phpfunc->setOpenSSLAlgorithms([]);
		$this->assertFalse(Aes::isSupported($phpfunc), 'No OpenSSL algorithms = not supported');
		$phpfunc->setOpenSSLAlgorithms($algorithms);

		// No required functions available = not supported
		$phpfunc->setFunctions([]);
		$this->assertFalse(Aes::isSupported($phpfunc), 'No required functions available = not supported');
		$phpfunc->setFunctions($functions_enabled);

		// Test with diminishing amounts of supported OpenSSL algos (=not supported) – for code coverage
		$temp = $algorithms;

		while (!empty($temp))
		{
			array_pop($temp);
			$phpfunc->setOpenSSLAlgorithms($temp);
			$this->assertFalse(Aes::isSupported($phpfunc));
		}

		$phpfunc->setOpenSSLAlgorithms($algorithms);

		// Test with diminishing amounts of supported functions (=not supported) – for code coverage
		$temp = $functions_enabled;

		while (!empty($temp))
		{
			array_pop($temp);
			$phpfunc->setFunctions($temp);
			$this->assertFalse(Aes::isSupported($phpfunc));
		}
	}

	/**
	 * @return  void
	 */
	public function testCryptProcess_OpenSSL_CBC()
	{
		if (!function_exists('openssl_encrypt'))
		{
			$this->markTestSkipped('OpenSSL is not supported on this system');
		}

		$phpfunc = new MockPhpfunc();
		$phpfunc->setFunctions([
			'openssl_get_cipher_methods',
			'openssl_random_pseudo_bytes',
			'openssl_cipher_iv_length',
			'openssl_encrypt',
			'openssl_decrypt',
			'hash',
			'hash_algos',
			'base64_encode',
			'base64_decode',
		]);

		// Regular string
		$str = 'THATISINSANE';

		$aes = new Aes('cbc', $phpfunc);
		$aes->setPassword('x123456789012345678901234567890x');
		$es  = $aes->encryptString($str, true);
		$ds  = $aes->decryptString($es, true);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);

		// UTF-8 data
		$str = 'Χρησιμοποιώντας μη λατινικούς χαρακτήρες';
		$es  = $aes->encryptString($str, false);
		$ds  = $aes->decryptString($es, false);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);

		// Using an odd sized keystring (using sha256 to convert it to a key)
		$aes = new Aes('The quick brown fox jumped over the lazy dog');
		$str = 'This is some very secret stuff that you are not supposed to transmit in clear text';
		$es  = $aes->encryptString($str, true);
		$ds  = $aes->decryptString($es, true);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);
	}

	/**
	 * @return  void
	 */
	public function testCryptProcess_OpenSSL_ECB()
	{
		if (!function_exists('openssl_encrypt'))
		{
			$this->markTestSkipped('OpenSSL is not supported on this system');
		}

		$phpfunc = new MockPhpfunc();
		$phpfunc->setFunctions([
			'openssl_get_cipher_methods',
			'openssl_random_pseudo_bytes',
			'openssl_cipher_iv_length',
			'openssl_encrypt',
			'openssl_decrypt',
			'hash',
			'hash_algos',
			'base64_encode',
			'base64_decode',
		]);

		// Regular string
		$str = 'THATISINSANE';

		$aes = new Aes('ecb', $phpfunc);
		$aes->setPassword('x123456789012345678901234567890x');
		$es  = $aes->encryptString($str, true);
		$ds  = $aes->decryptString($es, true);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);

		// UTF-8 data
		$str = 'Χρησιμοποιώντας μη λατινικούς χαρακτήρες';
		$es  = $aes->encryptString($str, false);
		$ds  = $aes->decryptString($es, false);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);

		// Using an odd sized keystring (using sha256 to convert it to a key)
		$aes = new Aes('The quick brown fox jumped over the lazy dog');
		$str = 'This is some very secret stuff that you are not supposed to transmit in clear text';
		$es  = $aes->encryptString($str, true);
		$ds  = $aes->decryptString($es, true);
		$ds  = rtrim($ds, "\000");
		$this->assertNotEquals($str, $es);
		$this->assertEquals($str, $ds);
	}

	/**
	 * Tests whether content encrypted with one adapter can be decrypted with another adapter
	 *
	 * @covers \FOF40\Encrypt\Aes
	 *
	 * @return  void
	 */
	public function testCryptCrossCompatibility()
	{
		$this->markTestSkipped('Reimplement this test once we add more adapters');
	}
}
