<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') || die;

// Load FOF if not already loaded
if (!defined('FOF40_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/FOF40/include.php'))
{
	throw new RuntimeException('This extension requires FOF 3.0.');
}

class plgUserFoftokenInstallerScript extends \FOF40\InstallScript\Plugin
{
}
