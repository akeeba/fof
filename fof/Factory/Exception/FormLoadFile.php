<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace  FOF40\Factory\Exception;

use Exception;
use Joomla\CMS\Language\Text;
use RuntimeException;

defined('_JEXEC') or die;

class FormLoadFile extends FormLoadGeneric
{
	public function __construct( $file = "", $code = 500, Exception $previous = null )
	{
		$message = Text::sprintf('LIB_FOF40_FORM_ERR_COULD_NOT_LOAD_FROM_FILE', $file);

		parent::__construct( $message, $code, $previous );
	}

}
