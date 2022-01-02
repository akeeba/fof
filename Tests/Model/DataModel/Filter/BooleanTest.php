<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel\Filter\Boolean;

use FOF40\Model\DataModel\Filter\Boolean;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'BooleanDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Filter\Boolean::<protected>
 * @covers      FOF40\Model\DataModel\Filter\Boolean::<private>
 * @package     FOF40\Tests\DataModel\Filter\Boolean
 */
class BooleanTest extends DatabaseTest
{
	/**
	 * @group           BooleanFilter
	 * @group           BooleanFilterIsEmpty
	 * @covers          FOF40\Model\DataModel\Filter\Boolean::isEmpty
	 * @dataProvider    BooleanDataprovider::getTestIsEmpty
	 */
	public function testIsEmpty($test, $check)
	{
		$msg    = 'Boolean::isEmpty %s - Case: ' . $check['case'];
		$filter = new Boolean(JoomlaFactory::getDbo(), (object) ['name' => 'test', 'type' => 'tinyint(1)']);

		$result = $filter->isEmpty($test['value']);

		$this->assertEquals($check['result'], $result, sprintf($msg, 'Failed to detect if a variable is empty'));
	}
}
