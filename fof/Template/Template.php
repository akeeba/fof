<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Template;

defined('_JEXEC') || die;

use FOF40\Container\Container;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use stdClass;

/**
 * A utility class to load view templates, media files and modules.
 *
 * @since    1.0
 */
class Template
{
	/**
	 * The component container
	 *
	 * @var  Container
	 */
	protected $container;

	/**
	 * Public constructor
	 *
	 * @param   Container  $container  The component container
	 */
	function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Add a CSS file to the page generated by the CMS
	 *
	 * @param   string       $uri      A path definition understood by parsePath, e.g. media://com_example/css/foo.css
	 * @param   string|null  $version  (optional) Version string to be added to the URL
	 * @param   string       $type     MIME type of the stylesheeet
	 * @param   string       $media    Media target definition of the style sheet, e.g. "screen"
	 * @param   array        $attribs  Array of attributes
	 *
	 * @see self::parsePath
	 *
	 * @return  void
	 */
	public function addCSS(string $uri, ?string $version = null, string $type = 'text/css', ?string $media = null, array $attribs = []): void
	{
		if ($this->container->platform->isCli())
		{
			return;
		}

		// Make sure we have attributes
		if (empty($attribs) || !is_array($attribs))
		{
			$attribs = [];
		}

		$url      = $this->parsePath($uri);
		$document = $this->container->platform->getDocument();

		$options = [
			'version' => is_null($version) ? null : ((string) $version),
		];

		$attribs['type'] = $type;

		if (!empty($media))
		{
			$attribs['media'] = $media;
		}

		$document->addStyleSheet($url, $options, $attribs);
	}

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
	 * When you are using $async = true there is no guarantee about the load order of the scripts. Whichever
	 * script loads first will be executed first. The order they appear on the page is completely irrelevant.
	 *
	 * If you set both async and defer to true it's the same as setting only async to true. That is to say, scripts will
	 * be loaded and executed in an order that is NOT guaranteed. Only set async for completely standalone scripts.
	 *
	 * If you are using defer you should pass script options using $this->container->platform->addScriptOptions(). This
	 * will add the script options as a JSON document in the document head. You can retrieve these options client-side
	 * with Joomla.getOptions() as long as the Joomla core JS has already been loaded, i.e. your deferred script is
	 * added AFTER Joomla's core code.
	 *
	 * @param   string       $uri      A path definition understood by parsePath, e.g. media://com_example/js/foo.js
	 * @param   boolean      $defer    Adds the defer attribute, see above
	 * @param   boolean      $async    Adds the async attribute, see above
	 * @param   string|null  $version  (optional) Version string to be added to the URL
	 * @param   string       $type     MIME type of the script
	 *
	 * @see self::parsePath
	 *
	 * @return  void
	 */
	public function addJS(string $uri, bool $defer = false, bool $async = false, ?string $version = null, string $type = 'text/javascript'): void
	{
		if ($this->container->platform->isCli())
		{
			return;
		}

		$url      = $this->container->template->parsePath($uri);
		$document = $this->container->platform->getDocument();

		// Setting both defer and async is nonsense. Only async makes sense in this case.
		if ($defer && $async)
		{
			$defer = false;
		}

		$options = [
			'version' => is_null($version) ? null : ((string) $version),
		];

		$attribs['defer'] = $defer;
		$attribs['async'] = $async;

		if (!empty($media))
		{
			$attribs['mime'] = $type;
		}

		$document->addScript($url, $options, $attribs);
	}

	/**
	 * Adds an inline JavaScript script to the page header
	 *
	 * @param   string  $script  The script content to add
	 * @param   string  $type    The MIME type of the script
	 */
	public function addJSInline(string $script, string $type = 'text/javascript'): void
	{
		if ($this->container->platform->isCli())
		{
			return;
		}

		$document = $this->container->platform->getDocument();

		if (!method_exists($document, 'addScriptDeclaration'))
		{
			return;
		}

		$document->addScriptDeclaration($script, $type);
	}

	/**
	 * Adds an inline stylesheet (inline CSS) to the page header
	 *
	 * @param   string  $css   The stylesheet content to add
	 * @param   string  $type  The MIME type of the script
	 */
	public function addCSSInline(string $css, string $type = 'text/css'): void
	{
		if ($this->container->platform->isCli())
		{
			return;
		}

		$document = $this->container->platform->getDocument();

		if (!method_exists($document, 'addStyleDeclaration'))
		{
			return;
		}

		$document->addStyleDeclaration($css, $type);
	}

	/**
	 * Creates a SEF compatible sort header. Standard Joomla function will add a href="#" tag, so with SEF
	 * enabled, the browser will follow the fake link instead of processing the onSubmit event; so we
	 * need a fix.
	 *
	 * @param   string    $text   Header text
	 * @param   string    $field  Field used for sorting
	 * @param   stdClass  $list   Object holding the direction and the ordering field
	 *
	 * @return  string  HTML code for sorting
	 */
	public function sefSort(string $text, string $field, stdClass $list): string
	{
		$sort = HTMLHelper::_('grid.sort', Text::_(strtoupper($text)) . '&nbsp;', $field, $list->order_Dir, $list->order);

		return str_replace('href="#"', 'href="javascript:void(0);"', $sort);
	}

	/**
	 * Parse a fancy path definition into a path relative to the site's root,
	 * respecting template overrides, suitable for inclusion of media files.
	 * For example, media://com_foobar/css/test.css is parsed into
	 * media/com_foobar/css/test.css if no override is found, or
	 * templates/mytemplate/media/com_foobar/css/test.css if the current
	 * template is called mytemplate and there's a media override for it.
	 *
	 * Regarding plugins, templates are searched inside the plugin's tmpl directory and the template's html directory.
	 * For instance considering plugin://system/example/something the files will be looked for in:
	 * plugins/system/example/tmpl/something.php
	 * templates/yourTemplate/html/plg_system_example/something.php
	 * Template paths for plugins are uncommon and not standard Joomla! practice. They make sense when you are
	 * implementing features of your component as plugins and they need to provide HTML output, e.g. some of the
	 * integration plugins we use in Akeeba Subscriptions.
	 *
	 * The valid protocols are:
	 *
	 *      media://        The media directory or a media override
	 *      plugin://    Given as plugin://pluginType/pluginName/template, e.g. plugin://system/example/something
	 *      admin://        Path relative to administrator directory (no overrides)
	 *      site://        Path relative to site's root (no overrides)
	 *      auto://      Automatically guess if it should be site:// or admin://
	 *      module://    The module directory or a template override (must be module://moduleName/templateName)
	 *
	 * @param   string   $path       Fancy path
	 * @param   boolean  $localFile  When true, it returns the local path, not the URL
	 *
	 * @return  string  Parsed path
	 *
	 * @see self::getAltPaths
	 *
	 */
	public function parsePath(string $path, bool $localFile = false): string
	{
		$separatorPosition = strpos($path, '://');

		if ($separatorPosition === false)
		{
			return $path;
		}

		$protocol = substr($path, 0, $separatorPosition);

		switch ($protocol)
		{
			case 'media':
			case 'plugin':
			case 'module':
			case 'auto':
			case 'admin':
			case 'site':
				break;

			default:
				return $path;
		}

		// Get the platform directories through the container
		$platformDirs = $this->container->platform->getPlatformBaseDirs();

		$url = $localFile ? (rtrim($platformDirs['root'], DIRECTORY_SEPARATOR) . '/') : $this->container->platform->URIroot();

		$altPaths = $this->getAltPaths($path);
		$filePath = $altPaths['normal'];

		// If JDEBUG is enabled, prefer the debug path, else prefer an alternate path if present
		if (defined('JDEBUG') && JDEBUG && isset($altPaths['debug']))
		{
			if (file_exists($platformDirs['public'] . '/' . $altPaths['debug']))
			{
				$filePath = $altPaths['debug'];
			}
		}
		elseif (isset($altPaths['alternate']))
		{
			if (file_exists($platformDirs['public'] . '/' . $altPaths['alternate']))
			{
				$filePath = $altPaths['alternate'];
			}
		}

		return $url . $filePath;
	}

	/**
	 * Parse a fancy path definition into a path relative to the site's root.
	 * It returns both the normal and alternative (template media override) path.
	 * For example, media://com_foobar/css/test.css is parsed into
	 * array(
	 *   'normal' => 'media/com_foobar/css/test.css',
	 *   'alternate' => 'templates/mytemplate/media/com_foobar/css//test.css'
	 * );
	 *
	 * The valid protocols are:
	 * media://        The media directory or a media override
	 * admin://        Path relative to administrator directory (no alternate)
	 * site://        Path relative to site's root (no alternate)
	 * auto://      Automatically guess if it should be site:// or admin://
	 * plugin://    The plugin directory or a template override (must be plugin://pluginType/pluginName/templateName)
	 * module://    The module directory or a template override (must be module://moduleName/templateName)
	 *
	 * @param   string  $path  Fancy path
	 *
	 * @return  array  Array of normal and alternate parsed path
	 */
	public function getAltPaths(string $path): array
	{
		$protoAndPath = explode('://', $path, 2);

		if (count($protoAndPath) < 2)
		{
			$protocol = 'media';
		}
		else
		{
			$protocol = $protoAndPath[0];
			$path     = $protoAndPath[1];
		}

		if ($protocol == 'auto')
		{
			$protocol = $this->container->platform->isBackend() ? 'admin' : 'site';
		}

		$path = ltrim($path, '/' . DIRECTORY_SEPARATOR);

		switch ($protocol)
		{
			case 'media':
				// Do we have a media override in the template?
				$pathAndParams = explode('?', $path, 2);

				$ret = [
					'normal'    => 'media/' . $pathAndParams[0],
					'alternate' => $this->container->platform->getTemplateOverridePath('media:/' . $pathAndParams[0], false),
				];
				break;

			case 'plugin':
				// The path is pluginType/pluginName/viewTemplate
				$pathInfo     = explode('/', $path);
				$pluginType   = $pathInfo[0] ?? 'system';
				$pluginName   = $pathInfo[1] ?? 'foobar';
				$viewTemplate = $pathInfo[2] ?? 'default';

				$pluginSystemName = 'plg_' . $pluginType . '_' . $pluginName;

				$ret = [
					'normal'    => 'plugins/' . $pluginType . '/' . $pluginName . '/tmpl/' . $viewTemplate,
					'alternate' => $this->container->platform->getTemplateOverridePath($pluginSystemName . '/' . $viewTemplate, false),
				];

				break;

			case 'module':
				// The path is moduleName/viewTemplate
				$pathInfo     = explode('/', $path, 2);
				$moduleName   = $pathInfo[0] ?? 'foobar';
				$viewTemplate = $pathInfo[1] ?? 'default';

				$moduleSystemName = 'mod_' . $moduleName;
				$basePath         = $this->container->platform->isBackend() ? 'administrator/' : '';

				$ret = [
					'normal'    => $basePath . 'modules/' . $moduleSystemName . '/tmpl/' . $viewTemplate,
					'alternate' => $this->container->platform->getTemplateOverridePath($moduleSystemName . '/' . $viewTemplate, false),
				];

				break;

			case 'admin':
				$ret = [
					'normal' => 'administrator/' . $path,
				];
				break;

			default:
			case 'site':
				$ret = [
					'normal' => $path,
				];
				break;
		}

		// For CSS and JS files, add a debug path if the supplied file is compressed
		$filesystem = $this->container->filesystem;
		$ext        = $filesystem->getExt($ret['normal']);

		if (in_array($ext, ['css', 'js']))
		{
			$file = basename($filesystem->stripExt($ret['normal']));

			/*
			 * Detect if we received a file in the format name.min.ext
			 * If so, strip the .min part out, otherwise append -uncompressed
			 */

			if (strlen($file) > 4 && strrpos($file, '.min', '-4'))
			{
				$position = strrpos($file, '.min', '-4');
				$filename = str_replace('.min', '.', $file, $position) . $ext;
			}
			else
			{
				$filename = $file . '-uncompressed.' . $ext;
			}

			// Clone the $ret array so we can manipulate the 'normal' path a bit
			$t1   = (object) $ret;
			$temp = clone $t1;
			unset($t1);
			$temp       = (array) $temp;
			$normalPath = explode('/', $temp['normal']);
			array_pop($normalPath);
			$normalPath[] = $filename;
			$ret['debug'] = implode('/', $normalPath);
		}

		return $ret;
	}

	/**
	 * Returns the contents of a module position
	 *
	 * @param   string  $position  The position name, e.g. "position-1"
	 * @param   int     $style     Rendering style, see ModuleRenderer::render
	 *
	 * @return  string  The contents of the module position
	 */
	public function loadPosition(string $position, int $style = -2): string
	{
		$document = $this->container->platform->getDocument();

		if (!($document instanceof Document))
		{
			return '';
		}

		if (!method_exists($document, 'loadRenderer'))
		{
			return '';
		}

		try
		{
			$renderer = $document->loadRenderer('module');
		}
		catch (\Exception $exc)
		{
			return '';
		}

		$params = ['style' => $style];

		$contents = '';

		foreach (ModuleHelper::getModules($position) as $mod)
		{
			$contents .= $renderer->render($mod, $params);
		}

		return $contents;
	}

	/**
	 * Render a module by name
	 *
	 * @param   string  $moduleName  The name of the module (real, eg 'Breadcrumbs' or folder, eg 'mod_breadcrumbs')
	 * @param   int     $style       The rendering style, see ModuleRenderer::render
	 *
	 * @return string  The rendered module
	 */
	public function loadModule(string $moduleName, int $style = -2): string
	{
		$document = $this->container->platform->getDocument();

		if (!($document instanceof Document))
		{
			return '';
		}

		if (!method_exists($document, 'loadRenderer'))
		{
			return '';
		}

		try
		{
			$renderer = $document->loadRenderer('module');
		}
		catch (\Exception $exc)
		{
			return '';
		}

		$params = ['style' => $style];

		$mod = ModuleHelper::getModule($moduleName);

		if (empty($mod))
		{
			return '';
		}

		return $renderer->render($mod, $params);
	}

	/**
	 * Performs SEF routing on a non-SEF URL, returning the SEF URL.
	 *
	 * When the $merge option is set to true the option, view, layout and format parameters of the current URL and the
	 * requested route will be merged. For example, assuming that current url is
	 * http://example.com/index.php?option=com_foo&view=cpanel
	 * then
	 * $template->route('view=categories&layout=tree');
	 * will result to
	 * http://example.com/index.php?option=com_foo&view=categories&layout=tree
	 *
	 * If $merge is unspecified (null) we will auto-detect the intended behavior. If you haven't specified option and
	 * one of view or task we will merge. Otherwise no merging takes place. This covers most use cases of FOF 3.0.
	 *
	 * @param   string  $route  The parameters string
	 * @param   bool    $merge  Should I perform parameter merging?
	 *
	 * @return  string  The human readable, complete url
	 */
	public function route(string $route = '', bool $merge = null): string
	{
		$route = trim($route);

		/**
		 * Backwards compatibility with FOF 3.0: if the merge option is unspecified we will auto-detect the behaviour.
		 * If option and either one of view or task are present we won't merge.
		 */
		if (is_null($merge))
		{
			$hasOption = (strpos($route, 'option=') !== false);
			$hasView   = (strpos($route, 'view=') !== false);
			$hasTask   = (strpos($route, 'task=') !== false);

			$merge = !($hasOption && ($hasView || $hasTask));
		}

		if ($merge)
		{
			// Handle special cases before trying to merge
			if ($route == 'index.php' || $route == 'index.php?')
			{
				$result = 'index.php';
			}
			elseif (substr($route, 0, 1) == '&')
			{
				$url  = Uri::getInstance();
				$vars = [];
				parse_str($route, $vars);

				$url->setQuery(array_merge($url->getQuery(true), $vars));

				$result = 'index.php?' . $url->getQuery();
			}
			else
			{
				$url   = Uri::getInstance();
				$props = $url->getQuery(true);

				// Strip 'index.php?'
				if (substr($route, 0, 10) == 'index.php?')
				{
					$route = substr($route, 10);
				}

				// Parse route
				$parts = [];
				parse_str($route, $parts);
				$result = [];

				// Check to see if there is component information in the route if not add it

				if (!isset($parts['option']) && isset($props['option']))
				{
					$result[] = 'option=' . $props['option'];
				}

				// Add the layout information to the route only if it's not 'default'

				if (!isset($parts['view']) && isset($props['view']))
				{
					$result[] = 'view=' . $props['view'];

					if (!isset($parts['layout']) && isset($props['layout']))
					{
						$result[] = 'layout=' . $props['layout'];
					}
				}

				// Add the format information to the URL only if it's not 'html'

				if (!isset($parts['format']) && isset($props['format']) && $props['format'] != 'html')
				{
					$result[] = 'format=' . $props['format'];
				}

				// Reconstruct the route

				if (!empty($route))
				{
					$result[] = $route;
				}

				$result = 'index.php?' . implode('&', $result);
			}
		}
		else
		{
			$result = $route;
		}

		return Route::_($result);
	}
}
