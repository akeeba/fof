<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\View;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\View\DataView\RawStub;

require_once __DIR__ . '/RawDataprovider.php';

/**
 * @covers      FOF40\View\DataView\Raw::<protected>
 * @covers      FOF40\View\DataView\Raw::<private>
 * @package     FOF40\Tests\DataView\Raw
 */
class RawTest extends FOFTestCase
{
	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::__construct
	 * @dataProvider    RawDataprovider::getTest__construct
	 */
	public function test__construct($test, $check)
	{
		$msg = 'Raw::__construct %s - Case: ' . $check['case'];

		$platform             = static::$container->platform;
		$platform::$uriBase   = 'www.example.com';
		$platform::$template  = 'fake_test_template';
		$platform::$isCli     = $test['mock']['isCli'];
		$platform::$authorise = function () {
			return false;
		};

		$view = new RawStub(static::$container);

		$permissions = ReflectionHelper::getValue($view, 'permissions');

		$this->assertEquals($check['permissions'], $permissions, sprintf($msg, 'Failed to set the permissions'));
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getLists
	 */
	public function testGetLists()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = (object) ['order' => '', 'order_Dir' => ''];

		ReflectionHelper::setValue($view, 'lists', $value);

		$this->assertSame($value, $view->getLists(), 'Raw::getLists Failed to return the internal lists');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getPerms
	 */
	public function testGetPerms()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = (object) ['edit' => true];

		ReflectionHelper::setValue($view, 'permissions', $value);

		$this->assertSame($value, $view->getPerms(), 'Raw::getPerms Failed to return the internal permissions');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getPagination
	 */
	public function testGetPagination()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = (object) ['test' => 'test'];

		ReflectionHelper::setValue($view, 'pagination', $value);

		$this->assertSame($value, $view->getPagination(), 'Raw::getPagination Failed to return the internal pagination');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getItems
	 */
	public function testGetItems()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = ['test', 'dummy'];

		ReflectionHelper::setValue($view, 'items', $value);

		$this->assertSame($value, $view->getItems(), 'Raw::getItems Failed to return the internal item list');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getItem
	 */
	public function testGetItem()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = (object) ['id' => 1, 'dummy' => 'test'];

		ReflectionHelper::setValue($view, 'item', $value);

		$this->assertSame($value, $view->getItem(), 'Raw::getItem Failed to return the internal item');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getItemCount
	 */
	public function testGetItemCount()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = 5;

		ReflectionHelper::setValue($view, 'itemCount', $value);

		$this->assertSame($value, $view->getItemCount(), 'Raw::getItemCount Failed to return the amount of items');
	}

	/**
	 * @group           DataViewRaw
	 * @covers          FOF40\View\DataView\Raw::getPageParams
	 */
	public function testGetPageParams()
	{
		$platform            = static::$container->platform;
		$platform::$uriBase  = 'www.example.com';
		$platform::$template = 'fake_test_template';

		$view = new RawStub(static::$container);

		$value = (object) ['test' => 1, 'dummy' => 'test'];

		ReflectionHelper::setValue($view, 'pageParams', $value);

		$this->assertSame($value, $view->getPageParams(), 'Raw::getPageParams Failed to return the internal item');
	}
}

