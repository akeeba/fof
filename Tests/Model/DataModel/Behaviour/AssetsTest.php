<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Assets;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Stubs\Model\DataModelStub;
use Joomla\CMS\Factory as JoomlaFactory;

require_once 'AssetsDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Assets::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Assets::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Assets
 */
class AssetsTest extends DatabaseTest
{
	/**
	 * @group           Behaviour
	 * @group           AssetsOnAfterSave
	 * @covers          FOF40\Model\DataModel\Behaviour\Assets::onAfterSave
	 * @dataProvider    AssetsDataprovider::getTestOnAfterSave
	 */
	public function testOnAfterSave($test, $check)
	{
		$msg = 'Assets::onAfterBuildQuery %s - Case: ' . $check['case'];
		$db  = JoomlaFactory::getDbo();

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model      = new DataModelStub(static::$container, $config);
		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Assets($dispatcher);

		$model->setAssetsTracked($test['track']);

		if ($test['load'])
		{
			$model->find($test['load']);
		}

		if ($test['rules'])
		{
			$model->setRules($test['rules']);
		}

		$query       = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
		$beforeTotal = $db->setQuery($query)->loadResult();

		$result = $behavior->onAfterSave($model);

		$this->assertTrue($result, sprintf($msg, 'Should always return true'));

		$asset = null;

		if ($check['count'] == 0)
		{
			$query      = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
			$afterTotal = $db->setQuery($query)->loadResult();

			$this->assertEquals(0, $beforeTotal - $afterTotal, sprintf($msg, 'Wrong number of assets saved'));
		}
		else
		{
			// Let's check what has been saved
			$query = $db->getQuery(true)
				->select('id, rules')
				->from('#__assets')
				->where('name = ' . $db->q($model->getAssetName()));
			$asset = $db->setQuery($query)->loadObject();

			$this->assertEquals($check['count'], (int) (!is_null($asset)), sprintf('Wrong number of assets saved'));
		}

		if (!is_null($check['rules']))
		{
			$this->assertEquals($check['rules'], $asset->rules, sprintf($msg, 'Wrong rule stored'));
		}

		if ($asset)
		{
			$asset_field = $model->getFieldAlias('asset_id');
			$model->find($test['load']);

			$this->assertEquals($asset->id, $model->$asset_field, sprintf($msg, 'Asset id not stored inside the model'));
		}
	}

	/**
	 * @group           Behaviour
	 * @group           AssetsOnAfterBind
	 * @covers          FOF40\Model\DataModel\Behaviour\Assets::onAfterBind
	 * @dataProvider    AssetsDataprovider::getTestOnAfterBind
	 */
	public function testOnAfterBind($test, $check)
	{
		$msg = 'Assets::onAfterBuildQuery %s - Case: ' . $check['case'];

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model      = new DataModelStub(static::$container, $config);
		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Assets($dispatcher);

		$model->setAssetsTracked($test['track']);

		if ($test['load'])
		{
			$model->find($test['load']);
		}

		$return = $behavior->onAfterBind($model, $test['bind']);

		$rules = $model->getRules();

		$this->assertTrue($return, sprintf($msg, 'Returned a wrong value'));

		if (empty($check['rules']))
		{
			$this->assertEquals($check['rules'], (string) $rules, sprintf($msg, 'Set rules wrong'));
		}
		else
		{
			$this->assertJsonStringEqualsJsonString($check['rules'], (string) $rules, sprintf($msg, 'Set rules wrong'));
		}
	}

	/**
	 * @group           Behaviour
	 * @group           AssetsOnBeforeDelete
	 * @covers          FOF40\Model\DataModel\Behaviour\Assets::onBeforeDelete
	 * @dataProvider    AssetsDataprovider::getTestOnBeforeDelete
	 */
	public function testOnBeforeDelete($test, $check)
	{
		$msg = 'Assets::onBeforeDelete %s - Case: ' . $check['case'];
		$db  = JoomlaFactory::getDbo();

		$config = [
			'idFieldName' => $test['tableid'],
			'tableName'   => $test['table'],
		];

		$model      = new DataModelStub(static::$container, $config);
		$dispatcher = $model->getBehavioursDispatcher();
		$behavior   = new Assets($dispatcher);

		$model->setAssetsTracked($test['track']);

		if ($test['load'])
		{
			$model->find($test['load']);
		}

		if ($check['exception'])
		{
			$this->setExpectedException('FOF40\Model\DataModel\Exception\NoAssetKey');
		}

		$query       = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
		$beforeTotal = $db->setQuery($query)->loadResult();

		$result = $behavior->onBeforeDelete($model, $test['id']);

		$this->assertTrue($result, sprintf($msg, 'Returned a wrong value'));

		$query      = $db->getQuery(true)->select('COUNT(*)')->from('#__assets');
		$afterTotal = $db->setQuery($query)->loadResult();

		$this->assertEquals($check['count'], $beforeTotal - $afterTotal, sprintf($msg, 'Deleted a wrong number of assets'));
	}
}
