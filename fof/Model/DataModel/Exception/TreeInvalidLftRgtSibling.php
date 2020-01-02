<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace  FOF40\Model\DataModel\Exception;

use Exception;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class TreeInvalidLftRgtSibling extends TreeInvalidLftRgt
{
	public function __construct( $message = '', $code = 500, Exception $previous = null )
	{
		if (empty($message))
		{
			$message = Text::_('LIB_FOF40_MODEL_ERR_TREE_INVALIDLFTRGT_SIBLING');
		}

		parent::__construct( $message, $code, $previous );
	}

}
