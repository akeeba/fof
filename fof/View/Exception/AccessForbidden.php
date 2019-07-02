<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\View\Exception;

use Exception;
use Joomla\CMS\Language\Text;
use RuntimeException;

defined('_JEXEC') or die;

/**
 * Exception thrown when the access to the requested resource is forbidden under the current execution context
 */
class AccessForbidden extends RuntimeException
{
	public function __construct(string $message = "", int $code = 403, Exception $previous = null)
	{
		if (empty($message))
		{
			$message = Text::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
		}

		parent::__construct($message, $code, $previous);
	}

}
