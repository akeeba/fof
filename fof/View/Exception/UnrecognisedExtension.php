<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace  FOF40\View\Exception;

use Exception;
use InvalidArgumentException;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Exception thrown when we can't figure out which engine to use for a view template
 */
class UnrecognisedExtension extends InvalidArgumentException
{
	public function __construct(string $path, int $code = 500, Exception $previous = null)
	{
		$message = Text::sprintf('LIB_FOF40_VIEW_UNRECOGNISEDEXTENSION', $path);

		parent::__construct($message, $code, $previous);
	}
}
