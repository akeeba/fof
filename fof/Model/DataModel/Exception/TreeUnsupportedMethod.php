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

class TreeUnsupportedMethod extends \LogicException
{
	public function __construct( $method = '', $code = 500, Exception $previous = null )
	{
		$message = Text::sprintf('LIB_FOF40_MODEL_ERR_TREE_UNSUPPORTEDMETHOD', $method);

		parent::__construct( $message, $code, $previous );
	}

}
