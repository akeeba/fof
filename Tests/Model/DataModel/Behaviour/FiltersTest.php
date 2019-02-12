<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Filters;
use FOF40\Tests\Helpers\DatabaseTest;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'FiltersDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Filters::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Filters::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Filters
 */
class FiltersTest extends DatabaseTest
{
	/**
	 * @group           Behaviour
	 * @group           FiltersOnAfterBuildQuery
	 * @covers          FOF40\Model\DataModel\Behaviour\Filters::onAfterBuildQuery
	 * @dataProvider    FiltersDataprovider::getTestOnAfterBuildQuery
	 */
	public function testOnAfterBuildQuery($test, $check)
	{
		//\PHPUnit_Framework_Error_Warning::$enabled = false;

		$msg = 'Filters::onAfterBuildQuery %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['getState'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->method('getState')->willReturnCallback(function ($key, $default = null) use ($test) {
			if (isset($test['mock']['state'][$key]))
			{
				return $test['mock']['state'][$key];
			}

			return $default;
		});

		$model->setIgnoreRequest($test['ignore']);

		$query      = JoomlaFactory::getDbo()->getQuery(true)->select('*')->from('test');
		$dispatcher = $model->getBehavioursDispatcher();
		$filter     = new Filters($dispatcher);

		$filter->onAfterBuildQuery($model, $query);

		$this->assertEquals($check['query'], trim((string) $query), sprintf($msg, 'Failed to build the query'));
	}
}
