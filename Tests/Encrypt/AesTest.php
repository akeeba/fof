<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
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

		// Just for code coverage
		$this->assertNotNull(Aes::isSupported());

		// All prerequisites met = supported
		$this->assertTrue(Aes::isSupported(), 'All prerequisites met = supported');
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

		// Regular string
		$str = 'This is a regular string';

		$aes = new Aes('cbc');
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

		// Regular string
		$str = 'This is a regular string';

		$aes = new Aes('ecb');
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
	 * Tests whether content encrypted with FOF3 can be decrypted with FOF4
	 *
	 * @covers \FOF40\Encrypt\Aes
	 *
	 * @return  void
	 */
	public function testCryptCrossCompatibility()
	{
		$password = 'jPwIOTJdPrUYPNuYFXHIi1YmnlYNOCkdxS1BN5tAr7Ebc7jRXb6OCpaKLXIy3geQ';
		$clearText = 'The quick brown fox jumped over the lazy dog';
		$fof3EncryptedCBC = '/I7Fmu3gy1bOb3OhGyz5rfN3mG9wAuFh68bCSwkgGNfW0xZHsoX9ALnbaHcpuN9eFNJU0Pjt7TakC0G00X8+qg==';
		$fof3EncryptedEBC = 'kWY6hJ/a+xkh9xoMLStw5fakqrWOdKrg57sXRK9BOjDlkJoxQ7AbBQELR2BUOS975QNquFJoG4XGYhVsTg8r6A==';

		$aes   = new Aes('cbc');
		$aes->setPassword($password);
		$decrypted = rtrim($aes->decryptString($fof3EncryptedCBC), "\0");

		$this->assertEquals($clearText, $decrypted, "Cannot decrypt a string encrypted with CBC on FOF 3 using the same key");

		$aes   = new Aes('ebc');
		$aes->setPassword($password);
		$decrypted = rtrim($aes->decryptString($fof3EncryptedEBC), "\0");

		$this->assertEquals($clearText, $decrypted, "Cannot decrypt a string encrypted with EBC on FOF 3 using the same key");
	}
}
