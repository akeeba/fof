<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Controller\Exception;

defined('_JEXEC') or die;

use InvalidArgumentException;

/**
 * Exception thrown when the provided Model is not a DataModel
 */
class NotADataModel extends InvalidArgumentException
{
}
