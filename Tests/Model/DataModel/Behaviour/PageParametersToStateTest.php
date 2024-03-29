<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Input\Input;
use FOF40\Model\DataModel\Behaviour\PageParametersToState;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\TestContainer;
use FOF40\Tests\Stubs\Model\DataModelStub;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;

require_once 'PageParametersToStateDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\PageParametersToState::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\PageParametersToState::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\PageParametersToState
 */
class PageParametersToStateTest extends DatabaseTest
{
	protected $savedApplication;

	public function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();
	}

	/**
	 * @group           Behaviour
	 * @group           PageParametersToStateOnAfterConstruct
	 * @covers          FOF40\Model\DataModel\Behaviour\PageParametersToState::onAfterConstruct
	 * @dataProvider    PageParametersToStateDataprovider::getTestOnAfterConstruct
	 */
	public function testOnAfterConstruct($test, $check)
	{
		$msg = 'PageParametersToState::onAfterConstruct %s - Case: ' . $check['case'];

		// Use a table without assets support, otherwise we have to mock the application
		// while we need it to set the state
		$config = [
			'idFieldName' => 'foftest_bare_id',
			'tableName'   => '#__foftest_bares',
		];

		$container = new TestContainer([
			'componentName' => 'com_fakeapp',
			'input'         => new Input($test['input']),
		]);

		/** @var \FOF40\Tests\Helpers\TestJoomlaPlatform $platform */
		$platform                           = $container->platform;
		$platform::$isAdmin                 = $test['mock']['admin'];
		$platform::$getUserStateFromRequest = 'do_not_mock';
		$platform::$user                    = new User(99);
		$platform::$user->id                = 99;

		$model = new DataModelStub($container, $config);

		foreach ($test['state'] as $key => $value)
		{
			$model->setState($key, $value);
		}

		$dispatcher = $model->getBehavioursDispatcher();

		$pageparams = new PageParametersToState($dispatcher);

		$fakeApp = new ClosureHelper([
			'getParams' => function () use ($test) {
				return new Registry($test['params']);
			},
		]);

		JoomlaFactory::$application = $fakeApp;

		$pageparams->onAfterConstruct($model);

		foreach ($check['state'] as $key => $value)
		{
			$this->assertEquals($value, $model->getState($key), sprintf($msg, 'Failed to set the correct value for the key: ' . $key));
		}
	}

	protected function tearDown()
	{
		$this->restoreFactoryState();

		parent::tearDown();
	}
}
