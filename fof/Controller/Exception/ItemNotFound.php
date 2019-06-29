<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Controller\Exception;

defined('_JEXEC') or die;

use RuntimeException;

/**
 * Exception thrown when we can't find the requested item in a read task
 */
class ItemNotFound extends RuntimeException
{

}
