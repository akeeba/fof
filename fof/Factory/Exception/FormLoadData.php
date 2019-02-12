<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace  FOF40\Factory\Exception;

use Exception;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class FormLoadData extends FormLoadGeneric
{
	public function __construct( $message = "", $code = 500, Exception $previous = null )
	{
		if (empty($message))
		{
			$message = Text::_('LIB_FOF40_FORM_ERR_COULD_NOT_LOAD_FROM_DATA');
		}

		parent::__construct( $message, $code, $previous );
	}

}
