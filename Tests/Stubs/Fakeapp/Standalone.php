<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


/**
 * This class if used when we have to test the loading of classes that do not use the autoloader
 */
class Standalone
{
	/**
	 * This method is used in {@link CallbackTest::testGetCallbackResults()} to test the callback
	 * to a class method
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public static function formCallback($data)
	{
		return $data;
	}

	/**
	 * This method is used in {@link GenericListTest::testGetOptions} to test fetching the options
	 * from a class method
	 */
	public static function getOptions()
	{
		$options = [
			'first'  => 'First item',
			'second' => 'Second item',
		];

		return $options;
	}
}
