<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Layout;

use Joomla\CMS\Factory as JoomlaFactory;

class LayoutHelperTestProvider
{
	/**
	 * @return array
	 */
	public static function getTestRender()
	{
		$fakeBase      = realpath(__DIR__ . '/../_data/layout/layout');
		$templateBase  = realpath(__DIR__ . '/../_data/layout/templates/system/html/layouts');
		$platformSetup = [
			'templateSuffixes' => ['.fof'],
			'template'         => 'system',
			'baseDirs'         => [
				'root'   => realpath(__DIR__ . '/../_data/layout'),
				'public' => realpath(__DIR__ . '/../_data/layout'),
				'admin'  => realpath(__DIR__ . '/../_data/layout/administrator'),
				'tmp'    => JoomlaFactory::getConfig()->get('tmp_path'),
				'log'    => JoomlaFactory::getConfig()->get('tmp_path'),
			],
		];

		// $layoutId, $platformSetup, $expectedPath, $message
		return [
			['test.foo', $platformSetup, 'FOO', 'Getting a simple layout'],
			['test.bar', $platformSetup, 'BAR.FOF', 'Getting a simple layout with platform extension'],
			[
				'test.overridden', $platformSetup, 'OVERRIDDEN',
				'Getting a simple overridden layout without platform extension',
			],
			['test.baz', $platformSetup, 'BAZ.FOF', 'Getting a simple overridden layout with a platform extension'],
			[
				'test.bat', $platformSetup, 'BAT.FOF',
				'Platform extension has priority over template override without platform extension',
			],
		];
	}
}
