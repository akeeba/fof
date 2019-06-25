<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Encrypt;

// Protect from unauthorized access
defined('_JEXEC') or die();

interface RandvalInterface
{
	/**
	 * Returns a cryptographically secure random value.
	 *
	 * @param int $bytes How many random bytes do you want to be returned?
	 *
	 * @return string
	 */
	public function generate(int $bytes = 32): string;
}
