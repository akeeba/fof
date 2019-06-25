<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Exception;

use Exception;
use Joomla\CMS\Language\Text;
use RuntimeException;

defined('_JEXEC') or die;

class DispatcherNotFound extends RuntimeException
{
	public function __construct(string $dispatcherClass, int $code = 500, Exception $previous = null)
	{
		$message = Text::sprintf('LIB_FOF40_DISPATCHER_ERR_NOT_FOUND', $dispatcherClass);

		parent::__construct($message, $code, $previous);
	}

}
