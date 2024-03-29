<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace  FOF40\View\DataView;

defined('_JEXEC') || die;

use FOF40\Container\Container;
use Joomla\CMS\Pagination\Pagination;

interface DataViewInterface
{
	/**
	 * Determines if the current Joomla! version and your current table support AJAX-powered drag and drop reordering.
	 * If they do, it will set up the drag & drop reordering feature.
	 *
	 * @return  boolean|array  False if not supported, otherwise a table with necessary information (saveOrder: should
	 *                           you enable DnD reordering; orderingColumn: which column has the ordering information).
	 */
	public function hasAjaxOrderingSupport();

	/**
	 * Returns the internal list of useful variables to the benefit of header fields.
	 *
	 * @return \stdClass
	 */
	public function getLists();

	/**
	 * Returns a reference to the permissions object of this view
	 *
	 * @return \stdClass
	 */
	public function getPerms();

	/**
	 * Returns a reference to the pagination object of this view
	 *
	 * @return Pagination
	 */
	public function getPagination();

	/**
	 * Method to get the view name
	 *
	 * The model name by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the model
	 *
	 * @throws  \Exception
	 */
	public function getName();

	/**
	 * Returns a reference to the container attached to this View
	 *
	 * @return  Container
	 */
	public function &getContainer();

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed $var The output to escape.
	 *
	 * @return  string  The escaped value.
	 */
	public function escape($var);

	/**
	 * Returns the task being rendered by the view
	 *
	 * @return  string
	 */
	public function getTask();

	/**
	 * Get the layout.
	 *
	 * @return  string  The layout name
	 */
	public function getLayout();

	/**
	 * Add a JS script file to the page generated by the CMS.
	 *
	 * There are three combinations of defer and async (see http://www.w3schools.com/tags/att_script_defer.asp):
	 * * $defer false, $async true: The script is executed asynchronously with the rest of the page
	 *   (the script will be executed while the page continues the parsing)
	 * * $defer true, $async false: The script is executed when the page has finished parsing.
	 * * $defer false, $async false. (default) The script is loaded and executed immediately. When it finishes
	 *   loading the browser continues parsing the rest of the page.
	 *
	 * When you are using $defer = true there is no guarantee about the load order of the scripts. Whichever
	 * script loads first will be executed first. The order they appear on the page is completely irrelevant.
	 *
	 * @param   string  $uri     A path definition understood by parsePath, e.g. media://com_example/js/foo.js
	 * @param   string  $version (optional) Version string to be added to the URL
	 * @param   string  $type    MIME type of the script
	 * @param   boolean $defer   Adds the defer attribute, see above
	 * @param   boolean $async   Adds the async attribute, see above
	 *
	 * @return  $this  Self, for chaining
	 */
	public function addJavascriptFile($uri, $version = null, $type = 'text/javascript', $defer = false, $async = false);

	/**
	 * Adds an inline JavaScript script to the page header
	 *
	 * @param   string  $script  The script content to add
	 * @param   string  $type    The MIME type of the script
	 *
	 * @return  $this  Self, for chaining
	 */
	public function addJavascriptInline($script, $type = 'text/javascript');

	/**
	 * Add a CSS file to the page generated by the CMS
	 *
	 * @param   string  $uri      A path definition understood by parsePath, e.g. media://com_example/css/foo.css
	 * @param   string  $version  (optional) Version string to be added to the URL
	 * @param   string  $type     MIME type of the stylesheeet
	 * @param   string  $media    Media target definition of the style sheet, e.g. "screen"
	 * @param   array   $attribs  Array of attributes
	 *
	 * @return  $this  Self, for chaining
	 */
	public function addCssFile($uri, $version = null, $type = 'text/css', $media = null, $attribs = array());

	/**
	 * Adds an inline stylesheet (inline CSS) to the page header
	 *
	 * @param   string  $css   The stylesheet content to add
	 * @param   string  $type  The MIME type of the script
	 *
	 * @return  $this  Self, for chaining
	 */
	public function addCssInline($css, $type = 'text/css');
}
