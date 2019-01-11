<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF40\Model\DataModel\Exception;

use Exception;

defined('_JEXEC') or die;

class TreeUnsupportedMethod extends \LogicException
{
	public function __construct( $method = '', $code = 500, Exception $previous = null )
	{
		$message = \JText::sprintf('LIB_FOF40_MODEL_ERR_TREE_UNSUPPORTEDMETHOD', $method);

		parent::__construct( $message, $code, $previous );
	}

}
