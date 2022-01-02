<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Enabled;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\User\User;

require_once 'EnabledDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Enabled::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Enabled::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Enabled
 */
class EnabledTest extends DatabaseTest
{
	/**
	 * @group           Behaviour
	 * @group           EnabledOnBeforeBuildQuery
	 * @covers          FOF40\Model\DataModel\Behaviour\Enabled::onBeforeBuildQuery
	 * @dataProvider    EnabledDataprovider::getTestOnBeforeBuildQuery
	 */
	public function testOnBeforeBuildQuery($test, $check)
	{
		$msg = 'Own::onAfterBuildQuery %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model = new DataModelStub(static::$container, $config);

		$query      = JoomlaFactory::getDbo()->getQuery(true)->select('*')->from('test');
		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Enabled($dispatcher);

		$behavior->onBeforeBuildQuery($model, $query);

		$where = ReflectionHelper::getValue($model, 'whereClauses');

		$this->assertCount($check['count'], $where, sprintf($msg, 'Failed to set the where'));
	}

	/**
	 * @group           Behaviour
	 * @group           EnabledOnAfterLoad
	 * @covers          FOF40\Model\DataModel\Behaviour\Enabled::onAfterLoad
	 * @dataProvider    EnabledDataprovider::getTestOnAfterLoad
	 */
	public function testOnAfterLoad($test, $check)
	{
		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$platform            = static::$container->platform;
		$platform::$user     = new User(99);
		$platform::$user->id = 99;

		$model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
			->setMethods(['reset', 'getFieldValue'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');
		$model->method('getFieldValue')->willReturn($test['mock']['enabled']);

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Enabled($dispatcher);

		$keys = [];
		$behavior->onAfterLoad($model, $keys);
	}
}
