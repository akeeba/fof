<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace  FOF40\Model\DataModel\Exception;

use Exception;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class NoContentType extends \UnexpectedValueException
{
	public function __construct( $className, $code = 500, Exception $previous = null )
	{
		$message = Text::sprintf('LIB_FOF40_MODEL_ERR_NOCONTENTTYPE', $className);

		parent::__construct( $message, $code, $previous );
	}

}
