<?php
// ====================================================================================================================
// Configuration
// ====================================================================================================================
use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\Installer\Installer as JoomlaInstaller;
use Joomla\CMS\Log\Log;
use Joomla\Input\Cli as JoomlaInputCli;
use Joomla\Input\Input as JoomlaInput;
use Joomla\Registry\Registry;

$sitePath           = '/var/www/test3/cli';
$stableVersionPath  = '/var/www/test3/000/fof-3.3.9';
$installVersionPath = '/var/www/test3/000/dev';

// Define ourselves as a parent file
define('_JEXEC', 1);
// Required by the CMS
define('DS', DIRECTORY_SEPARATOR);


// Load system defines
if (file_exists($sitePath . '/defines.php'))
{
	include_once $sitePath . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	$path = rtrim($sitePath, DIRECTORY_SEPARATOR);
	$rpos = strrpos($path, DIRECTORY_SEPARATOR);
	$path = substr($path, 0, $rpos);
	define('JPATH_BASE', $path);
	require_once JPATH_BASE . '/includes/defines.php';
}

// Load the rest of the framework include files
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
	require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
	require_once JPATH_LIBRARIES . '/import.php';
}
require_once JPATH_LIBRARIES . '/cms.php';

// Load the language files
$paths = [JPATH_ADMINISTRATOR, JPATH_ROOT];
$jlang = JoomlaFactory::getLanguage();
$jlang->load('lib_joomla', $paths[0], 'en-GB', true);

if (version_compare(JVERSION, '3.4.9999', 'ge'))
{
	// Joomla! 3.5 and later does not load the configuration.php unless you explicitly tell it to.
	JoomlaFactory::getConfig(JPATH_CONFIGURATION . '/configuration.php');
}

class FOFTestInstall extends CliApplication
{
	/**
	 * CliApplication didn't want to run on PHP CGI. I have my way of becoming
	 * VERY convincing. Now obey your true master, you petty class!
	 *
	 * @param JoomlaInputCli $input
	 * @param Registry       $config
	 * @param JDispatcher    $dispatcher
	 */
	public function __construct(JoomlaInput $input = null, Registry $config = null, JDispatcher $dispatcher = null)
	{
		// Close the application if we are not executed from the command line, Akeeba style (allow for PHP CGI)
		if (array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
		}

		$cgiMode = false;

		if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
		{
			$cgiMode = true;
		}

		// If a input object is given use it.
		if ($input instanceof JoomlaInput)
		{
			$this->input = $input;
		}
		// Create the input based on the application logic.
		else
		{
			if (class_exists('\Joomla\Input\Input'))
			{
				if ($cgiMode)
				{
					$query = "";
					if (!empty($_GET))
					{
						foreach ($_GET as $k => $v)
						{
							$query .= " $k";
							if ($v != "")
							{
								$query .= "=$v";
							}
						}
					}
					$query = ltrim($query);
					$argv  = explode(' ', $query);
					$argc  = count($argv);

					$_SERVER['argv'] = $argv;
				}

				$this->input = new JoomlaInputCli();
			}
		}

		// If a config object is given use it.
		if ($config instanceof Registry)
		{
			$this->config = $config;
		}
		// Instantiate a new configuration object.
		else
		{
			$this->config = new Registry;
		}

		// If a dispatcher object is given use it.
		if ($dispatcher instanceof JDispatcher)
		{
			$this->dispatcher = $dispatcher;
		}
		// Create the dispatcher based on the application logic.
		else
		{
			$this->loadDispatcher();
		}

		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData());

		// Set the execution datetime and timestamp;
		$this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
		$this->set('execution.timestamp', time());

		// Set the current directory.
		$this->set('cwd', getcwd());

		Log::addLogger([
			'logger'   => 'callback',
			'callback' => function (\Joomla\CMS\Log\LogEntry $entry) {
				switch ($entry->priority)
				{
					case Log::ERROR:
						$priority = 'ERROR';
						break;
					case Log::WARNING:
						$priority = 'WARNING';
						break;
					case Log::NOTICE:
						$priority = 'NOTICE';
						break;
					default:
						$priority = 'OTHER';
						break;
				}

				$burp = @ob_get_clean();
				echo "[ $priority :: {$entry->message} ]\n";
				flush();
				@ob_start();
				echo $burp;
			},
		], Log::ALL, ['jerror']);
	}


	protected function doExecute()
	{
		global $stableVersionPath, $installVersionPath;

		$tmpInstaller = new JoomlaInstaller();

		$isStable = $this->input->exists('stable');

		if ($isStable)
		{
			// Install the known good version
			$installResult = $tmpInstaller->install($stableVersionPath);

			return;
		}

		// Install the stable first. In another process to prevent having loaded the installation script already when installing the dev release.
		$myCommand = 'php ' . __FILE__ . ' --stable';
		passthru($myCommand);

		// Install the dev version
		define('AKEEBA_PACKAGE_INSTALLING', 1);
		$installResult = $tmpInstaller->install($installVersionPath);

		// Dump the installed version's version.txt file
		$path = JPATH_LIBRARIES . '/fof40/version.txt';
		readfile($path);
	}

	/**
	 * DO NOT EDIT BELOW THIS LINE
	 *
	 * This is necessary to install extensions from CLI
	 */

	public function flushAssets()
	{
		// This is an empty function since JInstall will try to flush the assets even if we're in CLI (!!!)
		return true;
	}

	public function getTemplate($params = false)
	{
		return '';
	}

	public function setHeader($name, $value, $replace = false)
	{
		return $this;
	}

	public function getCfg($name, $default = null)
	{
		return $this->get($name, $default);
	}

	public function getClientId()
	{
		return 1;
	}

	public function isClient($identifier)
	{
		return $identifier === 'administrator';
	}

	public function setUserState($key, $value)
	{
		$session  = &JoomlaFactory::getSession();
		$registry = &$session->get('registry');

		if (!is_null($registry))
		{
			return $registry->setValue($key, $value);
		}

		return null;
	}
}

try
{
	$cliApplication        = CliApplication::getInstance('FOFTestInstall');
	JoomlaFactory::$application = $cliApplication;
	$cliApplication->execute();
}
catch (Throwable $e)
{
	echo "\n\nERROR\n\n";
	echo $e->getCode() . '  --  ' . $e->getMessage() . "\n";
	echo $e->getFile() . ':' . $e->getLine() . "\n";
	echo $e->getTraceAsString() . "\n";
}
