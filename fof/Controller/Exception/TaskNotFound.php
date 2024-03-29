<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Controller\Exception;

defined('_JEXEC') || die;

use InvalidArgumentException;

/**
 * Exception thrown when we can't find a suitable method to handle the requested task
 */
class TaskNotFound extends InvalidArgumentException
{
}
