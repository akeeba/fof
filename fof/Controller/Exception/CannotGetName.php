<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Controller\Exception;

defined('_JEXEC') or die;

use RuntimeException;

/**
 * Exception thrown when we can't get a Controller's name
 */
class CannotGetName extends RuntimeException
{
}
