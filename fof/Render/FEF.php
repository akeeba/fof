<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace  FOF40\Render;

use FOF40\Container\Container;

defined('_JEXEC') or die;

/**
 * Renderer class for use with Akeeba FEF
 *
 * Renderer options
 * linkbar_style        Style for linkbars: joomla3|classic. Default: joomla3
 * load_fef             Load FEF CSS and JS? Set to false if you are loading it outside the renderer. Default: true
 * fef_reset            Should I reset the CSS styling for basic HTML elements inside the FEF container? Default: true
 * fef_dark             Should I load the FEF Dark Mode CSS and supporting JS? Default: false
 * custom_css           Comma-separated list of custom CSS files to load _after_ the main FEF CSS file, e.g.
 *                      media://com_foo/css/bar.min.css,media://com_foo/css/baz.min.css
 * remove_wrapper_classes  Comma-separated list of classes to REMOVE from the container
 * add_wrapper_classes     Comma-separated list of classes to ADD to the container
 *
 * Note: when Dark Mode is enabled the class akeeba-renderer-fef--dark is applied to the container DIV. You can use
 * remove_wrapper_classes to remove it e.g. when you want it to be enabled only through a JavaScript-powered toggle.
 *
 * @package FOF40\Render
 */
class FEF extends Joomla
{
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$helperFile = JPATH_SITE . '/media/fef/fef.php';

		if (!class_exists('AkeebaFEFHelper') && is_file($helperFile))
		{
			include_once $helperFile;
		}

		$this->priority	 = 20;
		$this->enabled	 = class_exists('AkeebaFEFHelper');
	}

	/**
	 * Echoes any HTML to show before the view template. We override it to load the CSS files required for FEF.
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 *
	 * @return  void
	 */
	public function preRender($view, $task)
	{
		$useReset    = $this->getOption('fef_reset', true);
		$useFEF      = $this->getOption('load_fef', true);
		$useDarkMode = $this->getOption('fef_dark', false);

		if ($useFEF && class_exists('AkeebaFEFHelper'))
		{
			\AkeebaFEFHelper::load($useReset);

			if ($useDarkMode)
			{
				$this->container->template->addCSS('media://fef/css/dark.min.css');
				$this->container->template->addJS('media://fef/js/darkmode.min.js');
			}
		}

		$this->loadCustomCss();

		parent::preRender($view, $task);
	}


	/**
	 * Opens the FEF styling wrapper element. Our component's output will be inside this wrapper.
	 *
	 * @param   array  $classes  An array of additional CSS classes to add to the outer page wrapper element.
	 *
	 * @return  void
	 */
	protected function openPageWrapper($classes)
	{
		$classes[] = 'akeeba-renderer-fef';

		// Check for FEF Dark Mode
		$useDarkMode = $this->getOption('fef_dark', false);

		if ($useDarkMode && !in_array('akeeba-renderer-fef--dark', $classes))
		{
			$classes[] = 'akeeba-renderer-fef--dark';
		}

		// Remove wrapper classes which are no longer used with FEF
		$removeClasses = $this->getOption('remove_wrapper_classes', [
			'j-toggle-main',
			'j-toggle-transition',
			'row-fluid'
		]);

		if (!is_array($removeClasses))
		{
			$removeClasses = explode(',', $removeClasses);
		}

		// Always remove the 'akeeba_renderer_joomla' wrapper class.
		$removeClasses[] = 'akeeba_renderer_joomla';

		$this->setOption('remove_wrapper_classes', implode(',', $removeClasses));

		// Call the parent method
		parent::openPageWrapper($classes);
	}

	/**
	 * Loads the custom CSS files defined in the custom_css renderer option.
	 */
	private function loadCustomCss()
	{
		$custom_css_raw = $this->getOption('custom_css', '');
		$custom_css_raw = trim($custom_css_raw);

		if (empty($custom_css_raw))
		{
			return;
		}

		$files        = explode(',', $custom_css_raw);
		$mediaVersion = $this->container->mediaVersion;

		foreach ($files as $file)
		{
			$file = trim($file);

			if (empty($file))
			{
				continue;
			}

			$this->container->template->addCSS($file, $mediaVersion);
		}
	}

}
