<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Date;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Date\TimezoneWrangler;
use Joomla\CMS\Factory as JoomlaFactory;


/**
 * @covers  \FOF40\Date\TimezoneWrangler::<protected>
 * @covers  \FOF40\Date\TimezoneWrangler::<private>
 */
class TimezoneWranglerTest extends FOFTestCase
{
	/**
	 * The timezone before we entered this test
	 *
	 * @var   string
	 */
	public static $savedOffset;

	/**
	 * Prepare for these tests by forcing the timezone in the Global Configuration and creating custom test users.
	 */
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		self::$savedOffset = JoomlaFactory::getConfig()->get('offset', 'America/New_York');

		// Set up the Server Timezone as US Eastern Time (GMT-05:00, follows GMT)
		JoomlaFactory::getConfig()->set('offset', 'America/New_York');

		// Custom users
		self::deleteCustomUsers();
		self::createCustomUsers();
	}

	/**
	 * Undo the setUpBeforeClass changes
	 */
	public static function tearDownAfterClass()
	{
		JoomlaFactory::getConfig()->set('offset', self::$savedOffset);

		parent::tearDownAfterClass();

		// Delete users 1001 etc
		self::deleteCustomUsers();
	}

	/**
	 * Deletes our custom users 1001 (user with timezone) and 1002 (user without timezone)
	 */
	private static function deleteCustomUsers()
	{
		$db = JoomlaFactory::getDbo();

		$query = $db->getQuery(true)
			->delete($db->qn('#__users'))
			->where($db->qn('id') . ' >= ' . $db->q(1000));
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true)
			->delete($db->qn('#__user_usergroup_map'))
			->where($db->qn('user_id') . ' >= ' . $db->q(1000));
		$db->setQuery($query)->execute();

		unset($query, $db);
	}

	/**
	 * Creates our custom users 1001 (user with timezone) and 1002 (user without timezone)
	 */
	private static function createCustomUsers()
	{
		$db = JoomlaFactory::getDbo();

		$userObject         = (object) [
			'id'       => 1001,
			'name'     => 'User 1001',
			'email'    => 'user1001@fof.test',
			'username' => 'user1001',
			'params'   => json_encode([
				'timezone' => 'Asia/Nicosia',
			]),
		];
		$userGroupMapObject = (object) [
			'user_id'  => 1001,
			'group_id' => 2,
		];
		$db->insertObject('#__users', $userObject, 'id');
		$db->insertObject('#__user_usergroup_map', $userGroupMapObject);

		$userObject         = (object) [
			'id'       => 1002,
			'name'     => 'User Without TZ',
			'email'    => 'user1002@fof.test',
			'username' => 'user1002',
		];
		$userGroupMapObject = (object) [
			'user_id'  => 1002,
			'group_id' => 2,
		];
		$db->insertObject('#__users', $userObject, 'id');
		$db->insertObject('#__user_usergroup_map', $userGroupMapObject);

		unset($userGroupMapObject, $userObject, $db);
	}

	public function testGetSetDefaultFormat()
	{
		$tzWrangler = new TimezoneWrangler(static::$container);

		$format = $tzWrangler->getDefaultFormat();
		$this->assertEquals('Y-m-d H:i:s T', $format, "Default tiemzone format must be 'Y-m-d H:i:s T'");

		$tzWrangler->setDefaultFormat('dummy');
		$format = $tzWrangler->getDefaultFormat();
		$this->assertEquals('dummy', $format, "Setting the default format must change the format in the class");
	}

	public function testGetSetDefaultTimezone()
	{
		$this->markTestIncomplete();
	}

	public function testSetDefaultTimezoneResetsCache()
	{
		$this->markTestIncomplete();
	}

	/**
	 * @dataProvider \FOF40\Tests\Date\TimezoneWranglerProvider::getTestGetApplicableTimezone()
	 */
	public function testGetApplicableTimezone($userID, $forced, $expected, $message)
	{
		$tzWrangler = new TimezoneWrangler(static::$container);

		if ($forced)
		{
			$tzWrangler->setForcedTimezone($forced);
		}

		$user = null;

		if (!empty($userID))
		{
			$user = JoomlaFactory::getUser($userID);
		}

		$tz = $tzWrangler->getApplicableTimezone($user);

		$this->assertTrue(is_object($tz), "getApplicableTimezone must return an object (Test case: '$message')");
		$this->assertInstanceOf('DateTimeZone', $tz, "getApplicableTimezone must return a DateTimeZone object (Test case: '$message')");

		$actual = $tz->getName();

		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @dataProvider \FOF40\Tests\Date\TimezoneWranglerProvider::getTestGetLocalDateTime
	 */
	public function testGetLocalDateTime($sourceTime, $timezone, $expected, $message)
	{
		$tzWrangler = new TimezoneWrangler(static::$container);
		$tzWrangler->setForcedTimezone($timezone);

		$localDate = $tzWrangler->getLocalDateTime(null, $sourceTime);

		$this->assertTrue(is_object($localDate), "Return must be an object");
		$this->assertInstanceOf('FOF40\Date\Date', $localDate, "Return must be a Date object");

		$actual = $localDate->format('Y-m-d H:i:s', true);
		$this->assertEquals($expected, $actual, $message);
	}

	/**
	 * @dataProvider \FOF40\Tests\Date\TimezoneWranglerProvider::getTestGetGMTDateTime
	 */
	public function testGetGMTDateTime($localTime, $timezone, $gmtTime, $message, $negativeTest = false)
	{
		$tzWrangler = new TimezoneWrangler(static::$container);
		$tzWrangler->setForcedTimezone($timezone);

		$gmtDate = $tzWrangler->getGMTDateTime(null, $localTime);

		$this->assertTrue(is_object($gmtDate), "Return must be an object");
		$this->assertInstanceOf('FOF40\Date\Date', $gmtDate, "Return must be a Date object");

		$actual = $gmtDate->format('Y-m-d H:i:s', true);

		if ($negativeTest)
		{
			$this->assertNotEquals($gmtTime, $actual, $message);

			return;
		}

		$this->assertEquals($gmtTime, $actual, $message);
	}

	public function testGetLocalTimeStamp()
	{
		$this->markTestIncomplete();
	}

	public function testGetGMTTimeStamp()
	{
		$this->markTestIncomplete();
	}
}
