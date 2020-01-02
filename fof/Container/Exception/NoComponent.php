<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Container\Exception;

use Exception;

defined('_JEXEC') or die;

class NoComponent extends \Exception
{
	public function __construct(string $message = "", int $code = 0, Exception $previous = null)
	{
		if (empty($message))
		{
			$message = 'No component specified building the Container object';
		}

		if (empty($code))
		{
			$code = 500;
		}

		parent::__construct($message, $code, $previous);
	}
}
