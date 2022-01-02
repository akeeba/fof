<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Input\Input;
use FOF40\Model\DataModel\Behaviour\Language;
use FOF40\Tests\Helpers\Application\AppWithLanguageFilter;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\Language\Language as JoomlaLanguage;

require_once 'LanguageDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Language::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Language::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Language
 */
class LanguageTest extends DatabaseTest
{
	public function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();
	}

	protected function tearDown()
	{
		parent::tearDown();

		$this->restoreFactoryState();
	}

	/**
	 * @group           Behaviour
	 * @group           LanguageOnBeforeBuildQuery
	 * @covers          FOF40\Model\DataModel\Behaviour\Language::onBeforeBuildQuery
	 * @dataProvider    LanguageDataprovider::getTestOnBeforeBuildQuery
	 */
	public function testOnBeforeBuildQuery($test, $check)
	{
		$msg = 'Language::onAfterBuildQuery %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$platform            = static::$container->platform;
		$platform::$isAdmin  = $test['mock']['admin'];
		$platform::$language = $this->getMockBuilder(JoomlaLanguage::class)
			->setMethods(['getTag'])
			->getMock();
		$platform::$language
			->expects($this->any())
			->method('getTag')
			->willReturn('en-GB');

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['blacklistFilters'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($this->exactly($check['blacklist']))->method('blacklistFilters');

		$query      = JoomlaFactory::getDbo()->getQuery(true)->select('*')->from('test');
		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Language($dispatcher);

		$fakeParams = (object) [
			'params' => [
				'remove_default_prefix' => $test['mock']['removePrefix'],
			],
		];

		ReflectionHelper::setValue($behavior, 'lang_filter_plugin', $fakeParams);

		if ($test['langField'])
		{
			$model->setFieldAlias('language', $test['langField']);
			$model->addKnownField($test['langField']);
		}

		// Null for the app without the method, any value to set the app with the method
		if (is_null($test['mock']['langFilter']))
		{
			// Let's mock our application
			$fakeApp = new ClosureHelper();
		}
		else
		{
			$fakeApp = new AppWithLanguageFilter($test['mock']['langFilter']);
		}

		$fakeApp->input = new Input($test['input']);

		JoomlaFactory::$application = $fakeApp;

		$behavior->onBeforeBuildQuery($model, $query);

		$where = ReflectionHelper::getValue($model, 'whereClauses');

		$this->assertEquals($check['where'], $where, sprintf($msg, 'Failed to set the correct where'));
	}

	/**
	 * @group           Behaviour
	 * @group           LanguageOnAfterLoad
	 * @covers          FOF40\Model\DataModel\Behaviour\Language::onAfterLoad
	 * @dataProvider    LanguageDataprovider::getTestOnAfterLoad
	 */
	public function testOnAfterLoad($test, $check)
	{
		$config = [
			'idFieldName' => 'foftest_foobar_id',
			'tableName'   => '#__foftest_foobars',
		];

		$platform            = static::$container->platform;
		$platform::$language = $this->getMockBuilder(JoomlaLanguage::class)
			->setMethods(['getTag'])
			->getMock();
		$platform::$language
			->expects($this->any())
			->method('getTag')
			->willReturn('en-GB');

		$model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
			->setMethods(['reset'])
			->setConstructorArgs([static::$container, $config])
			->getMock();

		$model->expects($this->exactly($check['reset']))->method('reset');

		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Language($dispatcher);
		$keys       = [];

		$fakeParams = (object) [
			'params' => [
				'remove_default_prefix' => $test['mock']['removePrefix'],
			],
		];

		ReflectionHelper::setValue($behavior, 'lang_filter_plugin', $fakeParams);

		if ($test['langField'])
		{
			$model->addKnownField('language');
			$model->setFieldValue('language', $test['langField']);
		}

		// Null for the app without the method, any value to set the app with the method
		if (is_null($test['mock']['langFilter']))
		{
			// Let's mock our application
			$fakeApp = new ClosureHelper();
		}
		else
		{
			$fakeApp = new AppWithLanguageFilter($test['mock']['langFilter']);
		}

		$fakeApp->input = new Input($test['input']);

		JoomlaFactory::$application = $fakeApp;

		$behavior->onAfterLoad($model, $keys);
	}
}
