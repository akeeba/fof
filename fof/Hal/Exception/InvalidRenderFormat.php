<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */



namespace FOF40\Hal\Exception;

use Exception;

defined('_JEXEC') or die;

class InvalidRenderFormat extends \RuntimeException
{
	public function __construct($format, $code = 500, Exception $previous = null)
	{
		$message = \JText::sprintf('LIB_FOF40_HAL_ERR_INVALIDRENDERFORMAT', $format);

		parent::__construct($message, $code, $previous);
	}
}
