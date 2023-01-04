<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Input;

use FOF40\Input\Input as FOFInput;
use Joomla\Input\Input as JoomlaInput;

abstract class InputProvider
{
	/**
	 * Returns a sample input array used for testing
	 *
	 * @return array
	 */
	public static function getSampleInputData()
	{
		return [
			'arrayVar'    => [
				'one' => 1,
				'two' => 2.34,
				'lol' => 'wut',
			],
			'cmdOK'       => 'This_Is_Sparta.300-HotgateS',
			'cmdNotOK'    => 'Implode This !@#$%^&*()String {}:"|<>?,/;\'\[]123', // ImplodeThisString123
			'intOK'       => 1,
			'intNotOK1'   => 1.15, // 1
			'intNotOK2'   => 'lol1.15', // 115
			'uintOK'      => 128,
			'uintNotOK1'  => -128, // 128
			'uintNotOK2'  => -128.123, // 128123
			'floatOK'     => '3.1415',
			'floatNotOK1' => 'sp3.1415abcd', // 3.1415
			'boolOK1'     => 1, // true
			'boolOK2'     => 0, // false
			'boolNotOK1'  => 'lolwut', // true
			'wordOK'      => 'This_Is_OK',
			'wordNotOK1'  => 'This Is Not OK', // ThisisNotOK
			'wordNotOK2'  => '50 bottles_of_rum', // bottles_of_rum
			'alnumOK'     => 'ThisIsOK123',
			'alnumNotOK1' => 'This Is Not OK 123', // ThisisNotOK123
			'base64OK'    => 'abcdef01234567890/+=',
			'base64NotOK' => 'abcdef01234567890/+={}[]:";\',.\\<>?', // abcdef01234567890/+=
			'stringOK'    => 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!',
			'stringOK2'   => 'Δοκιμή και με UTF8 χαρακτήρες',
			'html'        => '<p>In Code We Trust</p>',
			'path'        => JPATH_SITE . '/administrator',
			'pathNotOK'   => JPATH_SITE . '/../administrator', // JPATH_SITE . '//administrator'
			'raw'         => "Αποτέλεσμα με UTF8 χαρακτήρες\nκαι\tειδικούς χαρακτήρες\rελέγχου",
		];
	}

	public static function getTestConstructor()
	{
		// source, globals initialisation, match, message

		$sampleInputData = self::getSampleInputData();

		return [
			[$sampleInputData, [], $sampleInputData, 'Initialising with an array'],
			[(object) $sampleInputData, [], $sampleInputData, 'Initialising with an array'],
			[new JoomlaInput($sampleInputData), [], $sampleInputData, 'Initialising with a JInput object'],
			[new FOFInput($sampleInputData), [], $sampleInputData, 'Initialising with a FOF Input object'],
			['get', ['get' => $sampleInputData], $sampleInputData, 'Initialising with $_GET'],
			['post', ['post' => $sampleInputData], $sampleInputData, 'Initialising with $_POST'],
			['files', ['files' => $sampleInputData], $sampleInputData, 'Initialising with $_FILES'],
			['cookie', ['cookie' => $sampleInputData], $sampleInputData, 'Initialising with $_COOKIE'],
			['env', ['env' => $sampleInputData], $sampleInputData, 'Initialising with $_ENV'],
			['server', ['server' => $sampleInputData], $sampleInputData, 'Initialising with $_SERVER'],
			['request', ['request' => $sampleInputData], $sampleInputData, 'Initialising with $_REQUEST'],
			[null, ['request' => $sampleInputData], $sampleInputData, 'Initialising with implicit request'],
		];
	}

	public static function getTestGet()
	{
		$sampleData = self::getSampleInputData();

		// $key, $filter, $expected, $message
		return [
			['arrayVar', 'array', $sampleData['arrayVar'], 'Get ARRAY data'],
			['cmdOK', 'cmd', 'This_Is_Sparta.300-HotgateS', 'Get CMD data'],
			['cmdNotOK', 'cmd', 'ImplodeThisString123', 'Get CMD data, filter applied'],
			['intOK', 'int', 1, 'Get INT data'],
			['intNotOK1', 'int', 1, 'Get INT data, filter applied'],
			['intNotOK2', 'int', 1, 'Get INT data, filter applied (2)'],
			['uintOK', 'uint', 128, 'Get UINT data'],
			['uintNotOK1', 'uint', 128, 'Get UINT data, filter applied'],
			['uintNotOK2', 'uint', 128, 'Get UINT data, filter applied (2)'],
			['floatOK', 'float', 3.1415, 'Get FLOAT data'],
			['floatNotOK1', 'float', 3.1415, 'Get FLOAT data, filter applied'],
			['floatOK', 'double', 3.1415, 'Get DOUBLE data'],
			['floatNotOK1', 'double', 3.1415, 'Get DOUBLE data, filter applied'],
			['boolOK1', 'bool', true, 'Get BOOL data'],
			['boolOK2', 'bool', false, 'Get BOOL data'],
			['boolOK1', 'boolean', true, 'Get BOOLEAN data'],
			['boolOK2', 'boolean', false, 'Get BOOLEAN data'],
			['wordOK', 'word', 'This_Is_OK', 'Get WORD data'],
			['wordNotOK1', 'word', 'ThisIsNotOK', 'Get WORD data, filtered'],
			['wordNotOK2', 'word', 'bottles_of_rum', 'Get WORD data, filtered (2)'],
			['alnumOK', 'alnum', 'ThisIsOK123', 'Get ALNUM data'],
			['alnumNotOK1', 'alnum', 'ThisIsNotOK123', 'Get ALNUM data, filtered'],
			['base64OK', 'base64', 'abcdef01234567890/+=', 'Get BASE64 data'],
			['base64NotOK', 'base64', 'abcdef01234567890/+=', 'Get BASE64 data, filtered'],
			[
				'stringOK', 'string', 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!',
				'Get STRING data, explicit',
			],
			[
				'stringOK', 'whatever', 'Fifteen men on the dead man\'s chest-- ...Yo-ho-ho, and a bottle of rum!',
				'Get STRING data, implicit',
			],
			['stringOK2', 'string', 'Δοκιμή και με UTF8 χαρακτήρες', 'Get STRING data, explicit, UTF8'],
			['stringOK2', 'whatever', 'Δοκιμή και με UTF8 χαρακτήρες', 'Get STRING data, implicit, UTF8'],
			['html', 'html', 'In Code We Trust', 'Get HTML data, filtered'],
			//array('path', 'path', JPATH_SITE . '/administrator', 'Get PATH data'),
			//array('pathNotOK', 'path', JPATH_SITE . '//administrator', 'Get PATH data, filtered'),
			['raw', 'raw', "Αποτέλεσμα με UTF8 χαρακτήρες\nκαι\tειδικούς χαρακτήρες\rελέγχου", 'Get RAW data'],
			['IDoNotExist', 'raw', null, 'Not existing key returns default value'],
		];
	}

	public static function getTestMagicCall()
	{
		$originalTests = self::getTestGet();
		// The getArray is not a magic method, it's an entirely different thing. Looking for consistency in Joomla!? LOL!
		array_shift($originalTests);

		return $originalTests;
	}
}
