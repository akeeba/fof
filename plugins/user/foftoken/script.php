<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

defined('_JEXEC') or die;

// Load FOF if not already loaded
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('This extension requires FOF 3.0.');
}

class plgUserFoftokenInstallerScript extends FOF30\Utils\InstallScript\Plugin
{
}
