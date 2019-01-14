<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */



namespace FOF40\Render;

use FOF40\Container\Container;

defined('_JEXEC') or die;

class Joomla3 extends AkeebaStrapper
{
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->priority	 = 55;
		$this->enabled	 = version_compare(JVERSION, '3.0', 'ge');
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 *
	 * @return  void
	 */
	public function preRender($view, $task)
	{
		$input = $this->container->input;
		$platform = $this->container->platform;

		$format	 = $input->getCmd('format', 'html');

		if (empty($format))
		{
			$format	 = 'html';
		}

		if ($format != 'html')
		{
			return;
		}

		if ($platform->isCli())
		{
			return;
		}

		\JHtml::_('behavior.core');
		\JHTML::_('jquery.framework', true);

		// Wrap output in various classes
		$versionParts = explode('.', JVERSION);
		$minorVersion = $versionParts[0] . $versionParts[1];
		$majorVersion = $versionParts[0];

		$classes = array();

		if ($platform->isBackend())
		{
			$area = $platform->isBackend() ? 'admin' : 'site';
			$option = $input->getCmd('option', '');
			$viewForCssClass = $input->getCmd('view', '');
			$layout = $input->getCmd('layout', '');
			$taskForCssClass = $input->getCmd('task', '');

			$classes = array(
				'joomla-version-' . $majorVersion,
				'joomla-version-' . $minorVersion,
				$area,
				$option,
				'view-' . $view,
				'view-' . $viewForCssClass,
				'layout-' . $layout,
				'task-' . $task,
				'task-' . $taskForCssClass,
				// We have a floating sidebar, they said. It looks great, they said. They must've been blind, I say!
				'j-toggle-main',
				'j-toggle-transition',
				'row-fluid',
			);

			$classes = array_unique($classes);
		}

		$this->openPageWrapper($classes);

		// Render the submenu and toolbar
		if ($input->getBool('render_toolbar', true))
		{
			$this->renderButtons($view, $task);
			$this->renderLinkbar($view, $task);
		}
	}

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 *
	 * @return  void
	 */
	public function postRender($view, $task)
	{
		$input = $this->container->input;
		$platform = $this->container->platform;

		$format	 = $input->getCmd('format', 'html');

		if (empty($format))
		{
			$format	 = 'html';
		}

		if ($format != 'html')
		{
			return;
		}

		// Closing tag only if we're not in CLI
		if ($platform->isCli())
		{
			return;
		}

		// Closes akeeba-renderjoomla div
		$this->closePageWrapper();
	}

	/**
	 * Renders the submenu (link bar)
	 *
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 *
	 * @return  void
	 */
	protected function renderLinkbar($view, $task)
	{
		$style = $this->getOption('linkbar_style', 'joomla');

		switch ($style)
		{
			case 'joomla':
				$this->renderLinkbar_joomla($view, $task);
				break;

			case 'classic':
			default:
				$this->renderLinkbar_classic($view, $task);
				break;
		}
	}

	/**
	 * Opens a page wrapper. The component output will be inside this wrapper.
	 *
	 * @param   array  $classes  An array of additional CSS classes to add to the outer page wrapper element.
	 *
	 * @return  void
	 */
	protected function openPageWrapper($classes)
	{
		echo '<div id="akeeba-renderjoomla" class="' . implode($classes, ' ') . "\">\n";
	}

	/**
	 * Outputs HTML which closes the page wrappers opened with openPageWrapper.
	 *
	 * @return  void
	 */
	protected function closePageWrapper()
	{
		echo "</div>\n";
	}
}
