<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Factory\Exception;

use Exception;
use Joomla\CMS\Language\Text;
use RuntimeException;

defined('_JEXEC') or die;

class ViewNotFound extends RuntimeException
{
	public function __construct(string $viewClass, int $code = 500, Exception $previous = null)
	{
		$message = Text::sprintf('LIB_FOF40_VIEW_ERR_NOT_FOUND', $viewClass);

		parent::__construct($message, $code, $previous);
	}

}
