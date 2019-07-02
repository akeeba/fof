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
 * Exception thrown when we can't get a Controller's name
 */
class ModelNotFound extends RuntimeException
{
	public function __construct(string $path, string $viewName, int $code = 500, Exception $previous = null)
	{
		$message = Text::sprintf('LIB_FOF40_VIEW_MODELNOTINVIEW', $path, $viewName);

		parent::__construct($message, $code, $previous);
	}
}
