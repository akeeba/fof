<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers\Download;

abstract class FakeBase
{
	/**
	 * Return data which is $size bytes long.
	 *
	 * @param   int $size The number of bytes you want
	 *
	 * @return  string
	 */
	protected static function returnBytes($size)
	{
		$dummyData = file_get_contents(__DIR__ . '/data.dat');
		$dummySize = strlen($dummyData);

		$wholes    = ($size - ($size % $dummySize)) / $dummySize;
		$remainder = $size % $dummySize;
		$buffer    = '';

		if ($wholes)
		{
			for ($i = 0; $i < $wholes; $i++)
			{
				$buffer .= $dummyData;
			}
		}

		if ($remainder)
		{
			$buffer .= substr($dummyData, 0, $remainder);
		}

		return $buffer;
	}
}
