<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  render
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('FOF_INCLUDED') or die;

/**
 * Default Joomla! 1.5, 1.7, 2.5 view renderer class
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
class FOFRenderJoomla extends FOFRenderAbstract
{
	/**
	 * Public constructor. Determines the priority of this class and if it should be enabled
	 */
	public function __construct()
	{
		$this->priority	 = 50;
		$this->enabled	 = true;
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	public function preRender($view, $task, $input, $config = array())
	{
		$format	 = $input->getCmd('format', 'html');

		if (empty($format))
		{
			$format	 = 'html';
		}

		if ($format != 'html')
		{
			return;
		}

		if (!FOFPlatform::getInstance()->isCli())
		{
			// Wrap output in a Joomla-versioned div
			$version = new JVersion;
			$version = str_replace('.', '', $version->RELEASE);
			echo "<div class=\"joomla-version-$version\">\n";
		}

		// Render submenu and toolbar (only if asked to)
		if ($input->getBool('render_toolbar', true))
		{
			$this->renderButtons($view, $task, $input, $config);
			$this->renderLinkbar($view, $task, $input, $config);
		}
	}

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	public function postRender($view, $task, $input, $config = array())
	{
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
		if (FOFPlatform::getInstance()->isCli())
		{
			return;
		}

		echo "</div>\n";
	}

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormBrowse(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		JHtml::_('behavior.multiselect');

		// Getting all header row elements
		$headerFields = $form->getHeaderset();

		// Start the form
		$html				 = '';
		$filter_order		 = $form->getView()->getLists()->order;
		$filter_order_Dir	 = $form->getView()->getLists()->order_Dir;

		$html .= '<form action="index.php" method="post" name="adminForm" id="adminForm">' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="option" value="' . $input->getCmd('option') . '" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="view" value="' . FOFInflector::pluralize($input->getCmd('view')) . '" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="task" value="" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="boxchecked" value="" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="hidemainmenu" value="" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="filter_order" value="' . $filter_order . '" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="filter_order_Dir" value="' . $filter_order_Dir . '" />' . PHP_EOL;

		if (FOFPlatform::getInstance()->isFrontend() && ($input->getCmd('Itemid', 0) != 0))
		{
			$html .= "\t" . '<input type="hidden" name="Itemid" value="' . $input->getCmd('Itemid', 0) . '" />' . PHP_EOL;
		}

		$html .= "\t" . '<input type="hidden" name="' . JFactory::getSession()->getFormToken() . '" value="1" />' . PHP_EOL;

		// Start the table output
		$html .= "\t\t" . '<table class="adminlist" id="adminList">' . PHP_EOL;

		// Get form parameters
		$show_header		 = $form->getAttribute('show_header', 1);
		$show_filters		 = $form->getAttribute('show_filters', 1);
		$show_pagination	 = $form->getAttribute('show_pagination', 1);
		$norows_placeholder	 = $form->getAttribute('norows_placeholder', '');

		// Open the table header region if required
		if ($show_header || $show_filters)
		{
			$html .= "\t\t\t<thead>" . PHP_EOL;
		}

		// Pre-render the header and filter rows
		if ($show_header || $show_filters)
		{
			$header_html = '';
			$filter_html = '';

			foreach ($headerFields as $header)
			{
				// Make sure we have a header field. Under Joomla! 2.5 we cannot
				// render filter-only fields.
				$tmpHeader = $header->header;

				if (empty($tmpHeader))
				{
					continue;
				}

				$tdwidth = $header->tdwidth;

				if (!empty($tdwidth))
				{
					$tdwidth = 'width="' . $tdwidth . '"';
				}
				else
				{
					$tdwidth = '';
				}

				$header_html .= "\t\t\t\t\t<th $tdwidth>" . PHP_EOL;
				$header_html .= "\t\t\t\t\t\t" . $tmpHeader;
				$header_html .= "\t\t\t\t\t</th>" . PHP_EOL;

				$filter	 = $header->filter;
				$buttons = $header->buttons;
				$options = $header->options;

				$filter_html .= "\t\t\t\t\t<td>" . PHP_EOL;

				if (!empty($filter))
				{
					$filter_html .= "\t\t\t\t\t\t$filter" . PHP_EOL;

					if (!empty($buttons))
					{
						$filter_html .= "\t\t\t\t\t\t<nobr>$buttons</nobr>" . PHP_EOL;
					}
				}
				elseif (!empty($options))
				{
					$label		 = $header->label;
					$emptyOption = JHtml::_('select.option', '', '- ' . JText::_($label) . ' -');
					array_unshift($options, $emptyOption);
					$attribs	 = array(
						'onchange' => 'document.adminForm.submit();'
					);
					$filter		 = JHtml::_('select.genericlist', $options, $header->name, $attribs, 'value', 'text', $header->value, false, true);
					$filter_html .= "\t\t\t\t\t\t$filter" . PHP_EOL;
				}

				$filter_html .= "\t\t\t\t\t</td>" . PHP_EOL;
			}
		}

		// Render header if enabled
		if ($show_header)
		{
			$html .= "\t\t\t\t<tr>" . PHP_EOL;
			$html .= $header_html;
			$html .= "\t\t\t\t</tr>" . PHP_EOL;
		}

		// Render filter row if enabled
		if ($show_filters)
		{
			$html .= "\t\t\t\t<tr>";
			$html .= $filter_html;
			$html .= "\t\t\t\t</tr>";
		}

		// Close the table header region if required
		if ($show_header || $show_filters)
		{
			$html .= "\t\t\t</thead>" . PHP_EOL;
		}

		// Loop through rows and fields, or show placeholder for no rows
		$html .= "\t\t\t<tbody>" . PHP_EOL;
		$fields		 = $form->getFieldset('items');
		$num_columns = count($fields);
		$items		 = $form->getModel()->getItemList();

		if ($count = count($items))
		{
			$m = 1;

			foreach ($items as $i => $item)
			{
				$table_item = $form->getModel()->getTable();
				$table_item->reset();
				$table_item->bind($item);

				$form->bind($item);

				$m		 = 1 - $m;
				$class	 = 'row' . $m;

				$html .= "\t\t\t\t<tr class=\"$class\">" . PHP_EOL;

				$fields = $form->getFieldset('items');

				foreach ($fields as $field)
				{
					$field->rowid	 = $i;
					$field->item	 = $table_item;
					$class			 = $field->labelclass ? 'class ="' . $field->labelclass . '"' : '';
					$html .= "\t\t\t\t\t<td $class>" . $field->getRepeatable() . '</td>' . PHP_EOL;
				}

				$html .= "\t\t\t\t</tr>" . PHP_EOL;
			}
		}
		elseif ($norows_placeholder)
		{
			$html .= "\t\t\t\t<tr><td colspan=\"$num_columns\">";
			$html .= JText::_($norows_placeholder);
			$html .= "</td></tr>\n";
		}

		$html .= "\t\t\t</tbody>" . PHP_EOL;

		// Render the pagination bar, if enabled

		if ($show_pagination)
		{
			$pagination = $form->getModel()->getPagination();
			$html .= "\t\t\t<tfoot>" . PHP_EOL;
			$html .= "\t\t\t\t<tr><td colspan=\"$num_columns\">";

			if (($pagination->total > 0))
			{
				$html .= $pagination->getListFooter();
			}

			$html .= "</td></tr>\n";
			$html .= "\t\t\t</tfoot>" . PHP_EOL;
		}

		// End the table output
		$html .= "\t\t" . '</table>' . PHP_EOL;

		// End the form
		$html .= '</form>' . PHP_EOL;

		return $html;
	}

	/**
	 * Renders a FOFForm for a Read view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormRead(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		$html = $this->renderFormRaw($form, $model, $input, 'read');

		return $html;
	}

	/**
	 * Renders a FOFForm for an Edit view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormEdit(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		// Get the key for this model's table
		$key		 = $model->getTable()->getKeyName();
		$keyValue	 = $model->getId();

		JHTML::_('behavior.tooltip');

		$html = '';

		$validate	 = strtolower($form->getAttribute('validate'));
		$class		 = '';

		if (in_array($validate, array('true', 'yes', '1', 'on')))
		{
			JHTML::_('behavior.framework', true);
			JHTML::_('behavior.formvalidation');
			$class = 'form-validate ';
			$this->loadValidationScript($form);
		}

		// Check form enctype. Use enctype="multipart/form-data" to upload binary files in your form.
		$template_form_enctype = $form->getAttribute('enctype');

		if (!empty($template_form_enctype))
		{
			$enctype = ' enctype="' . $form->getAttribute('enctype') . '" ';
		}
		else
		{
			$enctype = '';
		}

		// Check form name. Use name="yourformname" to modify the name of your form.
		$formname = $form->getAttribute('name');

		if (empty($formname))
		{
			$formname = 'adminForm';
		}

		// Check form ID. Use id="yourformname" to modify the id of your form.
		$formid = $form->getAttribute('name');

		if (empty($formid))
		{
			$formid = 'adminForm';
		}

		$html .= '<form action="index.php" method="post" name="' . $formname .
			'" id="' . $formid . '"' . $enctype . ' class="' . $class .
			'">' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="option" value="' . $input->getCmd('option') . '" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="view" value="' . $input->getCmd('view', 'edit') . '" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="task" value="" />' . PHP_EOL;
		$html .= "\t" . '<input type="hidden" name="' . $key . '" value="' . $keyValue . '" />' . PHP_EOL;

		if (FOFPlatform::getInstance()->isFrontend() && ($input->getCmd('Itemid', 0) != 0))
		{
			$html .= "\t" . '<input type="hidden" name="Itemid" value="' . $input->getCmd('Itemid', 0) . '" />' . PHP_EOL;
		}

		$html .= "\t" . '<input type="hidden" name="' . JFactory::getSession()->getFormToken() . '" value="1" />' . PHP_EOL;

		$html .= $this->renderFormRaw($form, $model, $input, 'edit');
		$html .= '</form>';

		return $html;
	}

	/**
	 * Renders a raw FOFForm and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form     The form to render
	 * @param   FOFModel  $model     The model providing our data
	 * @param   FOFInput  $input     The input object
	 * @param   string    $formType  The form type e.g. 'edit' or 'read'
	 *
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormRaw(FOFForm &$form, FOFModel $model, FOFInput $input, $formType)
	{
		$html = '';

		foreach ($form->getFieldsets() as $fieldset)
		{
			$fields = $form->getFieldset($fieldset->name);

			if (isset($fieldset->class))
			{
				$class = 'class="' . $fieldset->class . '"';
			}
			else
			{
				$class = '';
			}

			$element = empty($fields) ? 'div' : 'fieldset';
			$html .= "\t" . '<' . $element . ' id="' . $fieldset->name . '" ' . $class . '>' . PHP_EOL;

			if (isset($fieldset->label) && !empty($fieldset->label))
			{
				$html .= "\t\t" . '<h3>' . JText::_($fieldset->label) . '</h3>' . PHP_EOL;
			}

			foreach ($fields as $field)
			{
				$label	 = $field->label;

				$html .= "<div class=\"fof-row\">";

				if (!is_null($label))
				{
					$html .= "\t\t\t" . $label . PHP_EOL;
				}

				if ($formType == 'read')
				{
					$html .= "\t\t\t" . $field->static . PHP_EOL;
				}
				elseif ($formType == 'edit')
				{
					$html .= "\t\t\t" . $field->input . PHP_EOL;
				}

				$html .= "</div>";
			}

			$element = empty($fields) ? 'div' : 'fieldset';
			$html .= "\t" . '</' . $element . '>' . PHP_EOL;
		}

		return $html;
	}

	/**
	 * Loads the validation script for an edit form
	 *
	 * @param   FOFForm  &$form  The form we are rendering
	 *
	 * @return  void
	 */
	protected function loadValidationScript(FOFForm &$form)
	{
		$message = $form->getView()->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));

		$js = <<<ENDJAVASCRIPT
Joomla.submitbutton = function(task)
{
	if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm')))
	{
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else {
		alert('$message');
	}
};
ENDJAVASCRIPT;

		$document = FOFPlatform::getInstance()->getDocument();

		if ($document instanceof JDocument)
		{
			$document->addScriptDeclaration($js);
		}
	}

	/**
	 * Renders the submenu (link bar)
	 *
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input object
	 * @param   array     $config  Extra configuration variables for the toolbar
	 *
	 * @return  void
	 */
	protected function renderLinkbar($view, $task, $input, $config = array())
	{
		// On command line don't do anything

		if (FOFPlatform::getInstance()->isCli())
		{
			return;
		}

		// Do not render a submenu unless we are in the the admin area
		$toolbar				 = FOFToolbar::getAnInstance($input->getCmd('option', 'com_foobar'), $config);
		$renderFrontendSubmenu	 = $toolbar->getRenderFrontendSubmenu();

		if (!FOFPlatform::getInstance()->isBackend() && !$renderFrontendSubmenu)
		{
			return;
		}

		$this->renderLinkbarItems($toolbar);
	}

	/**
	 * do the rendering job for the linkbar
	 *
	 * @param   FOFToolbar  $toolbar  A toolbar object
	 *
	 * @return  void
	 */
	protected function renderLinkbarItems($toolbar)
	{
		$links = $toolbar->getLinks();

		if (!empty($links))
		{
			foreach ($links as $link)
			{
				JSubMenuHelper::addEntry($link['name'], $link['link'], $link['active']);
			}
		}
	}

	/**
	 * Renders the toolbar buttons
	 *
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input object
	 * @param   array     $config  Extra configuration variables for the toolbar
	 *
	 * @return  void
	 */
	protected function renderButtons($view, $task, $input, $config = array())
	{
		// On command line don't do anything

		if (FOFPlatform::getInstance()->isCli())
		{
			return;
		}

		// Do not render buttons unless we are in the the frontend area and we are asked to do so
		$toolbar				 = FOFToolbar::getAnInstance($input->getCmd('option', 'com_foobar'), $config);
		$renderFrontendButtons	 = $toolbar->getRenderFrontendButtons();

		if (FOFPlatform::getInstance()->isBackend() || !$renderFrontendButtons)
		{
			return;
		}

		// Load main backend language, in order to display toolbar strings
		// (JTOOLBAR_BACK, JTOOLBAR_PUBLISH etc etc)
		FOFPlatform::getInstance()->loadTranslations('joomla');

		$title	 = JFactory::getApplication()->get('JComponentTitle');
		$bar	 = JToolBar::getInstance('toolbar');

		// Delete faux links, since if SEF is on, Joomla will follow the link instead of submitting the form
		$bar_content = str_replace('href="#"', '', $bar->render());

		echo '<div id="FOFHeaderHolder">', $bar_content, $title, '<div style="clear:both"></div>', '</div>';
	}
}
