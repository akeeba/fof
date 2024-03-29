<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


// Required to load FOF and Joomla!
use FOF40\Autoloader\Autoloader;
use FOF40\Tests\Helpers\TravisLogger;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\CMS\Input\Cli as JoomlaInputCli;
use Joomla\CMS\Session\Session;

define('_JEXEC', 1);
define('JDEBUG', 0);

if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', __DIR__);
}

// Include the FOF autoloader.
if (!class_exists('FOF40\\Autoloader\\Autoloader'))
{
	require_once __DIR__ . '/../fof/Autoloader/Autoloader.php';

	if (!class_exists('FOF40\\Autoloader\\Autoloader'))
	{
		echo 'ERROR: FOF Autoloader not found' . PHP_EOL;

		exit(1);
	}
}

require_once __DIR__ . '/../fof/Utils/helpers.php';

// Tell the FOF autoloader where to load test classes from (very useful for stubs!)
Autoloader::getInstance()->addMap('FOF40\\Tests\\', __DIR__);
Autoloader::getInstance()->addMap('Fakeapp\\', __DIR__ . '/Stubs/Fakeapp');
Autoloader::getInstance()->addMap('Dummyapp\\', __DIR__ . '/Stubs/Dummyapp');

TravisLogger::reset();
TravisLogger::log(4, 'Log reset');

// Include the Composer autoloader.
if (false == include_once __DIR__ . '/../vendor/autoload.php')
{
	echo 'ERROR: You need to install Composer and run `composer install` on FOF before running the tests.' . PHP_EOL;

	exit(1);
}

TravisLogger::log(4, 'Autoloader included');

// Don't report strict errors. This is needed because sometimes a test complains about arguments passed as reference
ini_set('zend.ze1_compatibility_mode', '0');
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

// Fix magic quotes on PHP 5.3
if (version_compare(PHP_VERSION, '5.4.0', 'lt'))
{
	ini_set('magic_quotes_runtime', 0);
}

// Fixed timezone to preserve our sanity
@date_default_timezone_set('UTC');

$jversion_test = getenv('JVERSION_TEST') ? getenv('JVERSION_TEST') : 'staging';
$isTravis      = getenv('TRAVIS');

TravisLogger::log(4, 'Including environment info. Joomla version: ' . $jversion_test);

require_once __DIR__ . '/environments.php';

if (!isset($environments[$jversion_test]))
{
	echo('Joomla environment ' . $jversion_test . ' not recognized');
	TravisLogger::log(4, 'Joomla environment ' . $jversion_test . ' not recognized');
	exit(1);
}

$siteroot = $environments[$jversion_test];

TravisLogger::log(4, 'Siteroot for this tests: ' . $siteroot);

if (!$siteroot && $isTravis)
{
	echo('Empty siteroot, we can not continue');
	TravisLogger::log(4, 'Empty siteroot, we can not continue');
	exit(1);
}

//Am I in Travis CI?
if ($isTravis)
{
	TravisLogger::log(4, 'Including special Travis configuration file');
	require_once __DIR__ . '/config_travis.php';

	// Set the test configuration site root if not set in travis
	if (!isset($fofTestConfig['site_root']))
	{
		$fofTestConfig['site_root'] = $siteroot;
	}
}
else
{
	if (!file_exists(__DIR__ . '/config.php'))
	{
		echo "Configuration file not found. Please copy the config.dist.php file and rename it to config.php\n";
		echo "Then update its contents with the connection details to your database";
		exit(1);
	}

	require_once __DIR__ . '/config.php';

	$siteroot = $fofTestConfig['site_root'];

	if (!$siteroot || !is_dir($siteroot))
	{
		echo('The site_root is empty or does not point to an existing folder. We can not continue');

		exit(1);
	}
}

if (!isset($fofTestConfig['host']) || !isset($fofTestConfig['user']) || !isset($fofTestConfig['password']) || !isset($fofTestConfig['db']))
{
	echo "Your config file is missing one or more required info. Please copy the config.dist.php file and rename it to config.php\n";
	echo "then update its contents with the connection details to your database";
	exit(1);
}

TravisLogger::log(4, 'Including defines.php from Joomla environment');

// Set up the Joomla! environment
if (file_exists($siteroot . '/defines.php'))
{
	include_once $siteroot . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', $siteroot);

	require_once JPATH_BASE . '/includes/defines.php';
}

// Bootstrap the CMS libraries.
TravisLogger::log(4, 'Bootstrap the CMS libraries.');
require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

// Since there is no configuration file inside Joomla cloned repo, we have to read the installation one...
TravisLogger::log(4, 'Including configuration.php-dist from Joomla environment');

$files = [
	JPATH_SITE . '/installation/configuration.php-dist',
	JPATH_SITE . '/configuration.php',
];

$config = null;

foreach ($files as $file)
{
	if (!file_exists($file) || !is_file($file) || !is_readable($file))
	{
		continue;
	}

	include_once $file;

	$config = JoomlaFactory::getConfig(JPATH_SITE . '/installation/configuration.php-dist');

	break;
}

if (is_null($config))
{
	throw new RuntimeException(sprintf("The Joomla installation in %s does not have a configuration.php-dist or configuration.php file.", JPATH_SITE));
}

TravisLogger::log(4, 'Changing values for the JConfig object');
// ... and then hijack some details
$dbtype = function_exists('mysqli_connect') ? 'mysqli' : 'mysql';
$config->set('dbtype', $dbtype);
$config->set('host', $fofTestConfig['host']);
$config->set('user', $fofTestConfig['user']);
$config->set('password', $fofTestConfig['password']);
$config->set('db', $fofTestConfig['db']);
$config->set('prefix', 'jos_');
$config->set('tmp_path', JPATH_ROOT . '/tmp');
$config->set('log_path', JPATH_ROOT . '/logs');
// Despite its name, this is the session STORAGE, NOT the session HANDLER. Because that somehow makes sense. NOT.
$config->set('session_handler', 'none');

// We need to set up the Session object
require_once 'Stubs/Session/FakeSession.php';
$sessionHandler = new JSessionHandlerFake();
$session        = Session::getInstance('none', [], $sessionHandler);
$input          = new JoomlaInputCli();
$dispatcher     = new JEventDispatcher();
$session->initialise($input, $dispatcher);
JoomlaFactory::$session = $session;

// Do I have a Joomla database schema ready? If not, let's import the installation SQL file
$db = JoomlaFactory::getDbo();

try
{
	TravisLogger::log(4, 'Checking if core tables are there');
	$db->setQuery('SHOW COLUMNS FROM `jos_assets`')->execute();
}
catch (JDatabaseExceptionConnecting $e)
{
	throw $e;
}
catch (Exception $e)
{
	TravisLogger::log(4, 'Core tables not found, attempt to create them');

	// Core table missing, let's import them
	$file    = JPATH_SITE . '/installation/sql/mysql/joomla.sql';
	$queries = $db->splitSql(file_get_contents($file));

	foreach ($queries as $query)
	{
		$query = trim($query);

		if (!$query)
		{
			continue;
		}

		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $e)
		{
			// Something went wrong, let's log the exception and then throw it again
			TravisLogger::log(4, 'An error occurred while creating core tables. Error: ' . $e->getMessage());
			throw $e;
		}
	}
}

TravisLogger::log(4, 'Create test specific tables');

// Let's use our class to create the schema
$importer = new \FOF40\Database\Installer(JoomlaFactory::getDbo(), JPATH_TESTS . '/Stubs/schema');
$importer->updateSchema();
unset($importer);

TravisLogger::log(4, 'Boostrap ended');
