<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\TransparentAuthentication;

use FOF40\Encrypt\Aes;
use FOF40\Encrypt\Totp;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\TransparentAuthentication\TransparentAuthentication;

/**
 * Class TransparentAuthenticationTest
 *
 * @package FOF40\Tests\TransparentAuthentication
 *
 * @coversDefaultClass \FOF40\TransparentAuthentication\TransparentAuthentication
 */
class TransparentAuthenticationTest extends FOFTestCase
{
	/** @var  TransparentAuthentication */
	protected $auth;

	/** @var  string The TOTP key */
	protected static $totpKey = 'OBXARH6TY6L6YT64';

	public function testConstruct()
	{
		$auth = new TransparentAuthentication(static::$container);

		$this->assertEquals(static::$container, ReflectionHelper::getValue($auth, 'container'));
	}

	public function testAddAuthenticationMethod()
	{
		$this->auth->setAuthenticationMethods([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
		]);

		// Try adding an existing method
		$this->auth->addAuthenticationMethod(TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext);

		$this->assertEquals([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
		], ReflectionHelper::getValue($this->auth, 'authenticationMethods'));

		// Try adding a non-existent method
		$this->auth->addAuthenticationMethod(TransparentAuthentication::Auth_HTTPBasicAuth_TOTP);

		$this->assertEquals([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
			TransparentAuthentication::Auth_HTTPBasicAuth_TOTP,
		], ReflectionHelper::getValue($this->auth, 'authenticationMethods'));

		// Try adding an existing method again
		$this->auth->addAuthenticationMethod(TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext);

		$this->assertEquals([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
			TransparentAuthentication::Auth_HTTPBasicAuth_TOTP,
		], ReflectionHelper::getValue($this->auth, 'authenticationMethods'));
	}

	public function testRemoveAuthenticationMethod()
	{
		$this->auth->setAuthenticationMethods([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
			TransparentAuthentication::Auth_HTTPBasicAuth_TOTP,
		]);

		// Try removing a non-existing method
		$this->auth->removeAuthenticationMethod(TransparentAuthentication::Auth_QueryString_Plaintext);

		$this->assertEquals([
			TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext,
			TransparentAuthentication::Auth_HTTPBasicAuth_TOTP,
		], ReflectionHelper::getValue($this->auth, 'authenticationMethods'));

		// Try removing an existing method
		$this->auth->removeAuthenticationMethod(TransparentAuthentication::Auth_HTTPBasicAuth_Plaintext);

		$this->assertEquals([
			1 => TransparentAuthentication::Auth_HTTPBasicAuth_TOTP,
		], ReflectionHelper::getValue($this->auth, 'authenticationMethods'));
	}

	/**
	 * @dataProvider GetTestGetTransparentAuthenticationCredentials
	 */
	public function testGetTransparentAuthenticationCredentials($inputData, $serverGlobals, $shouldSucceed)
	{
		$input = static::$container->input;

		// Clear input data
		ReflectionHelper::setValue($input, 'data', []);

		// Push input data if so defined
		if (!is_null($inputData))
		{
			ReflectionHelper::setValue($input, 'data', $inputData);
		}

		// Override server globals if necessary
		if (!is_null($serverGlobals))
		{
			foreach ($serverGlobals as $k => $v)
			{
				$_SERVER[$k] = $v;
			}
		}

		// Decode the transparent authentication information
		$result = $this->auth->getTransparentAuthenticationCredentials();

		if ($shouldSucceed)
		{
			$this->assertInternalType('array', $result);
			$this->assertArrayHasKey('username', $result);
			$this->assertArrayHasKey('password', $result);
			$this->assertEquals('FOF40test', $result['username']);
			$this->assertEquals('dummy', $result['password']);
		}
		else
		{
			$this->assertNull($result);
		}
	}

	public function GetTestGetTransparentAuthenticationCredentials()
	{
		// Let's do some TOTP encoding
		$totp      = new Totp();
		$otp       = $totp->getCode(static::$totpKey);
		$cryptoKey = hash('sha256', static::$totpKey . $otp);
		$aes       = new Aes($cryptoKey);

		$plainText_right           = json_encode(['username' => 'FOF40test', 'password' => 'dummy']);
		$plainText_missingPassword = json_encode(['username' => 'FOF40test']);
		$plainText_missingUsername = json_encode(['password' => 'dummy']);
		$plainText_crap            = 'crap_data';
		$encoded_right             = $aes->encryptString($plainText_right);
		$encoded_missingPassword   = $aes->encryptString($plainText_missingPassword);
		$encoded_missingUsername   = $aes->encryptString($plainText_missingUsername);
		$encoded_crap              = $aes->encryptString($plainText_crap);

		$otp             = $totp->getCode(static::$totpKey, time() - 86400);
		$cryptoKey       = hash('sha256', static::$totpKey . $otp);
		$aes             = new Aes($cryptoKey);
		$encodedOutdated = $aes->encryptString($plainText_right);

		// Input data, server globals, do I expect correct username/password
		return [
			// HTTP Basic Auth, plaintext
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40test',
					'PHP_AUTH_PW'   => 'dummy',
				],
				true,
			],
			// HTTP Basic Auth, missing username
			[
				null,
				[
					'PHP_AUTH_PW' => 'dummy',
				],
				false,
			],
			// HTTP Basic Auth, missing password
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40test',
				],
				false,
			],

			// Query string, plaintext
			[
				[
					'testAuth' => json_encode([
						'username' => 'FOF40test',
						'password' => 'dummy',
					]),
				],
				null,
				true,
			],
			// Query string, missing username
			[
				[
					'testAuth' => json_encode([
						'password' => 'dummy',
					]),
				],
				null,
				false,
			],
			// Query string, missing password
			[
				[
					'testAuth' => json_encode([
						'username' => 'FOF40test',
					]),
				],
				null,
				false,
			],
			// Query string, crap string
			[
				[
					'testAuth' => 'stupid_string_is_no_good_json_data',
				],
				null,
				false,
			],

			// Split query string
			[
				[
					'FOF40Username' => 'FOF40test',
					'FOF40Password' => 'dummy',
				],
				null,
				true,
			],
			// Split query string, missing username
			[
				[
					'FOF40Password' => 'dummy',
				],
				null,
				false,
			],
			// Split query string, missing password
			[
				[
					'FOF40Username' => 'FOF40test',
				],
				null,
				false,
			],
			// Split query string, junk fed
			[
				[
					'junk' => 'food',
				],
				null,
				false,
			],
			// HTTP Basic Auth, TOTP, correct
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => $encoded_right,
				],
				true,
			],
			// HTTP Basic Auth, TOTP, missing username
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => $encoded_missingUsername,
				],
				false,
			],
			// HTTP Basic Auth, TOTP, missing password
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => $encoded_missingPassword,
				],
				false,
			],
			// HTTP Basic Auth, TOTP, crap encoded data
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => $encoded_crap,
				],
				false,
			],
			// HTTP Basic Auth, TOTP, crap non-encoded data
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => 'this_is_crap_data',
				],
				false,
			],
			// HTTP Basic Auth, TOTP, outdated but otherwise correctly encrypted data
			[
				null,
				[
					'PHP_AUTH_USER' => 'FOF40user',
					'PHP_AUTH_PW'   => $encodedOutdated,
				],
				false,
			],

			// Query string, TOTP, correct
			[
				[
					'testAuth' => $encoded_right,
				],
				null,
				true,
			],
			// Query string, TOTP, missing username
			[
				[
					'testAuth' => $encoded_missingUsername,
				],
				null,
				false,
			],
			// Query string, TOTP, missing password
			[
				null,
				[
					'testAuth' => $encoded_missingPassword,
				],
				false,
			],
			// Query string, TOTP, crap encoded data
			[
				null,
				[
					'testAuth' => $encoded_crap,
				],
				false,
			],
			// Query string, TOTP, crap non-encoded data
			[
				[
					'testAuth' => 'this_is_crap_data',
				],
				null,
				false,
			],
			// Query string, TOTP, outdated but otherwise correctly encrypted data
			[
				[
					'testAuth' => $encodedOutdated,
				],
				null,
				false,
			],
		];
	}

	protected function setUp()
	{
		parent::setUp();

		$this->auth = new TransparentAuthentication(static::$container);
		$this->auth->setAuthenticationMethods([1, 2, 3, 4, 5]);
		$this->auth->setBasicAuthUsername('FOF40user');
		$this->auth->setQueryParam('testAuth');
		$this->auth->setQueryParamPassword('FOF40Password');
		$this->auth->setQueryParamUsername('FOF40Username');
		$this->auth->setTimeStep(30);
		$this->auth->setTotpKey(self::$totpKey);
	}
}
