<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace  FOF40\Model\DataModel\Exception;

defined('_JEXEC') || die;

use Exception;
use Joomla\CMS\Language\Text;

class TreeMethodOnlyAllowedInRoot extends \RuntimeException
{
	public function __construct( $method = '', $code = 500, Exception $previous = null )
	{
		$message = Text::sprintf('LIB_FOF40_MODEL_ERR_TREE_ONLYINROOT', $method);

		parent::__construct( $message, $code, $previous );
	}

}
