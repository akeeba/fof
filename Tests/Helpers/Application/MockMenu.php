<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Application;

use FOF40\Tests\Helpers\FOFTestCase;

class MockMenu
{
	protected static $data = [];

	/**
	 * Creates an instance of the mock AbstractMenu object.
	 *
	 * @param   FOFTestCase $test A test object.
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   3.4
	 */
	public static function create(FOFTestCase $test, $setDefault = true, $setActive = false)
	{
		// Collect all the relevant methods in AbstractMenu (work in progress).
		$methods = [
			'getItem',
			'setDefault',
			'getDefault',
			'setActive',
			'getActive',
			'getItems',
			'getParams',
			'getMenu',
			'authorise',
			'load',
		];

		// Create the mock.
		$mockObject = $test->getMockBuilder('\Joomla\CMS\Menu\AbstractMenu')
			->setMethods($methods)
			->setConstructorArgs([])
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		self::createMenuSampleData();

		$mockObject->expects($test->any())
			->method('getItem')
			->will($test->returnValueMap(self::prepareGetItemData()));

		$mockObject->expects($test->any())
			->method('getMenu')
			->will($test->returnValue(self::$data));

		if ($setDefault)
		{
			$mockObject->expects($test->any())
				->method('getDefault')
				->will($test->returnValueMap(self::prepareDefaultData()));
		}

		if ($setActive)
		{
			$mockObject->expects($test->any())
				->method('getActive')
				->will($test->returnValue(self::$data[$setActive]));
		}

		return $mockObject;
	}

	protected static function prepareGetItemData()
	{
		$return = [];

		foreach (self::$data as $id => $item)
		{
			$return[] = [$id, $item];
			$return[] = [(string) $id, $item];
		}

		return $return;
	}

	protected static function prepareDefaultData()
	{
		$return   = [];
		$return[] = ['en-GB', self::$data[45]];

		return $return;
	}

	protected static function prepareGetItemsData()
	{
		// Why this is clear here?
	}

	protected static function createMenuSampleData()
	{
		self::$data[42] = (object) [
			'id'           => '42',
			'menutype'     => 'testmenu',
			'title'        => 'Test1',
			'alias'        => 'test',
			'route'        => 'test',
			'link'         => 'index.php?option=com_test&view=test',
			'type'         => 'component',
			'level'        => '1',
			'language'     => '*',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '0',
			'component_id' => '1000',
			'parent_id'    => '0',
			'component'    => 'com_test',
			'tree'         => [42],
			'query'        => ['option' => 'com_test', 'view' => 'test'],
		];

		self::$data[43] = (object) [
			'id'           => '43',
			'menutype'     => 'testmenu',
			'title'        => 'Test2',
			'alias'        => 'test2',
			'route'        => 'test2',
			'link'         => 'index.php?option=com_test2&view=test',
			'type'         => 'component',
			'level'        => '1',
			'language'     => '*',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '0',
			'component_id' => '1000',
			'parent_id'    => '0',
			'component'    => 'com_test2',
			'tree'         => [43],
			'query'        => ['option' => 'com_test2', 'view' => 'test'],
		];

		self::$data[44] = (object) [
			'id'           => '44',
			'menutype'     => 'testmenu',
			'title'        => 'Submenu',
			'alias'        => 'sub-menu',
			'route'        => 'test2/sub-menu',
			'link'         => 'index.php?option=com_test2&view=test2',
			'type'         => 'component',
			'level'        => '2',
			'language'     => '*',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '0',
			'component_id' => '1000',
			'parent_id'    => '43',
			'component'    => 'com_test2',
			'tree'         => [43, 44],
			'query'        => ['option' => 'com_test2', 'view' => 'test2'],
		];

		self::$data[45] = (object) [
			'id'           => '45',
			'menutype'     => 'testmenu',
			'title'        => 'Home',
			'alias'        => 'home',
			'route'        => 'home',
			'link'         => 'index.php?option=com_test3&view=test3',
			'type'         => 'component',
			'level'        => '1',
			'language'     => '*',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '1',
			'component_id' => '1000',
			'parent_id'    => '0',
			'component'    => 'com_test3',
			'tree'         => [43, 44],
			'query'        => ['option' => 'com_test3', 'view' => 'test3'],
		];

		self::$data[46] = (object) [
			'id'           => '46',
			'menutype'     => 'testmenu',
			'title'        => 'Submenu',
			'alias'        => 'sub-menu',
			'route'        => 'test/sub-menu',
			'link'         => 'index.php?option=com_test&view=test2',
			'type'         => 'component',
			'level'        => '2',
			'language'     => '*',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '0',
			'component_id' => '1000',
			'parent_id'    => '42',
			'component'    => 'com_test',
			'tree'         => [42, 46],
			'query'        => ['option' => 'com_test', 'view' => 'test2'],
		];

		self::$data[47] = (object) [
			'id'           => '47',
			'menutype'     => 'testmenu',
			'title'        => 'English Test',
			'alias'        => 'english-test',
			'route'        => 'english-test',
			'link'         => 'index.php?option=com_test&view=test2',
			'type'         => 'component',
			'level'        => '1',
			'language'     => 'en-GB',
			'access'       => '1',
			'params'       => '{}',
			'home'         => '0',
			'component_id' => '1000',
			'parent_id'    => '0',
			'component'    => 'com_test',
			'query'        => ['option' => 'com_test', 'view' => 'test2'],
		];

		/**    self::$data[48] = (object) array(
		 * 'id'           => '48',
		 * 'menutype'     => '',
		 * 'title'        => '',
		 * 'alias'        => '',
		 * 'route'        => '',
		 * 'link'         => '',
		 * 'type'         => '',
		 * 'level'        => '',
		 * 'language'     => '',
		 * 'access'       => '',
		 * 'params'       => '',
		 * 'home'         => '',
		 * 'component_id' => '',
		 * 'parent_id'    => '',
		 * 'component'    => '',
		 * 'query'        => array());
		 *
		 * self::$data[49] = (object) array(
		 * 'id'           => '49',
		 * 'menutype'     => '',
		 * 'title'        => '',
		 * 'alias'        => '',
		 * 'route'        => '',
		 * 'link'         => '',
		 * 'type'         => '',
		 * 'level'        => '',
		 * 'language'     => '',
		 * 'access'       => '',
		 * 'params'       => '',
		 * 'home'         => '',
		 * 'component_id' => '',
		 * 'parent_id'    => '',
		 * 'component'    => '',
		 * 'query'        => array());
		 *
		 * self::$data[50] = (object) array(
		 * 'id'           => '50',
		 * 'menutype'     => '',
		 * 'title'        => '',
		 * 'alias'        => '',
		 * 'route'        => '',
		 * 'link'         => '',
		 * 'type'         => '',
		 * 'level'        => '',
		 * 'language'     => '',
		 * 'access'       => '',
		 * 'params'       => '',
		 * 'home'         => '',
		 * 'component_id' => '',
		 * 'parent_id'    => '',
		 * 'component'    => '',
		 * 'query'        => array());
		 *
		 * self::$data[51] = (object) array(
		 * 'id'           => '51',
		 * 'menutype'     => '',
		 * 'title'        => '',
		 * 'alias'        => '',
		 * 'route'        => '',
		 * 'link'         => '',
		 * 'type'         => '',
		 * 'level'        => '',
		 * 'language'     => '',
		 * 'access'       => '',
		 * 'params'       => '',
		 * 'home'         => '',
		 * 'component_id' => '',
		 * 'parent_id'    => '',
		 * 'component'    => '',
		 * 'query'        => array());**/
	}

}
