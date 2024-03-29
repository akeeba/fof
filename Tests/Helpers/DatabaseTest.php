<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Helpers;

use FOF40\Container\Container;
use Joomla\CMS\Factory as JoomlaFactory;

abstract class DatabaseTest extends \PHPUnit_Extensions_Database_TestCase
{
	/**
	 * @var            array    The Factory pointers saved before the execution of the test
	 */
	protected $savedFactoryState = [];

	/**
	 * Assigns mock callbacks to methods.
	 *
	 * @param   object $mockObject The mock object that the callbacks are being assigned to.
	 * @param   array  $array      An array of methods names to mock with callbacks.
	 *
	 * @return  void
	 *
	 * @note    This method assumes that the mock callback is named {mock}{method name}.
	 * @since   1.0
	 */
	public function assignMockCallbacks($mockObject, $array)
	{
		foreach ($array as $index => $method)
		{
			if (is_array($method))
			{
				$methodName = $index;
				$callback   = $method;
			}
			else
			{
				$methodName = $method;
				$callback   = [get_called_class(), 'mock' . $method];
			}

			$mockObject
				->method($methodName)
				->will($this->returnCallback($callback));
		}
	}

	/**
	 * Assigns mock values to methods.
	 *
	 * @param   object $mockObject   The mock object.
	 * @param   array  $array        An associative array of methods to mock with return values:<br />
	 *                               string (method name) => mixed (return value)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function assignMockReturns($mockObject, $array)
	{
		foreach ($array as $method => $return)
		{
			$mockObject
				->method($method)
				->will($this->returnValue($return));
		}
	}

	/**
	 * Returns the default database connection for running the tests.
	 *
	 * @return  \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
	 *
	 * @since   1.0
	 */
	protected function getConnection()
	{
		static $connection;

		if (!$connection)
		{
			$config = JoomlaFactory::getConfig();

			// P.A. Test database prefix is fixed with jos_ so we can setup common tables
			$options = [
				'driver'   => ((isset ($config)) && ($config->get('dbtype') != 'mysqli')) ? $config->get('dbtype') : 'mysql',
				'host'     => $config->get('host', '127.0.0.1'),
				'user'     => $config->get('user', 'utuser'),
				'password' => $config->get('password', 'ut1234'),
				'database' => $config->get('db', 'joomla_ut'),
				'prefix'   => 'jos_',
			];

			$pdo = new \PDO('mysql:host=' . $options['host'] . ';dbname=' . $options['database'], $options['user'], $options['password']);
			$pdo->exec("SET @@SESSION.sql_mode = '';");
			$connection = $this->createDefaultDBConnection($pdo, $options['database']);
		}

		return $connection;
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 *
	 * @since   1.0
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/../Stubs/schema/database.xml');
	}

	/**
	 * Returns the database operation executed in test setup.
	 *
	 * @return  \PHPUnit_Extensions_Database_Operation_Composite
	 *
	 * @since   1.0
	 */
	protected function getSetUpOperation()
	{
		// At the moment we can safely TRUNCATE tables, since we're not using InnoDB tables nor foreign keys
		// However if we ever need them, we can use our InsertOperation and TruncateOperation to suppress foreign keys
		return new \PHPUnit_Extensions_Database_Operation_Composite(
			[
				\PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE(),
				\PHPUnit_Extensions_Database_Operation_Factory::INSERT(),
			]
		);
	}

	/** @var Container A container suitable for unit testing */
	public static $container = null;

	public static function setUpBeforeClass()
	{
		self::rebuildContainer();
	}

	public static function tearDownAfterClass()
	{
		static::$container = null;
	}

	public static function rebuildContainer()
	{
		static::$container = null;
		static::$container = new TestContainer([
			'componentName' => 'com_fakeapp',
		]);
	}

	public function setUp()
	{
		parent::setUp();

		// Since we're creating the platform only when we instantiate the test class, any modification
		// will be carried over in the other tests, so we have to manually reset the platform before
		// running any other test
		$platform = static::$container->platform;

		if (method_exists($platform, 'reset'))
		{
			$platform->reset();
		}
	}

	/**
	 * Saves the Factory pointers
	 *
	 * @return void
	 */
	protected function saveFactoryState()
	{
		// We have to clone the objects, otherwise it's useless to save them
		$this->savedFactoryState['application'] = is_object(JoomlaFactory::$application) ? clone JoomlaFactory::$application : JoomlaFactory::$application;
		$this->savedFactoryState['config']      = is_object(JoomlaFactory::$config) ? clone JoomlaFactory::$config : JoomlaFactory::$config;
		$this->savedFactoryState['dates']       = JoomlaFactory::$dates;
		$this->savedFactoryState['session']     = is_object(JoomlaFactory::$session) ? clone JoomlaFactory::$session : JoomlaFactory::$session;
		$this->savedFactoryState['language']    = is_object(JoomlaFactory::$language) ? clone JoomlaFactory::$language : JoomlaFactory::$language;
		$this->savedFactoryState['document']    = is_object(JoomlaFactory::$document) ? clone JoomlaFactory::$document : JoomlaFactory::$document;
		$this->savedFactoryState['acl']         = is_object(JoomlaFactory::$acl) ? clone JoomlaFactory::$acl : JoomlaFactory::$acl;
		$this->savedFactoryState['database']    = is_object(JoomlaFactory::$database) ? clone JoomlaFactory::$database : JoomlaFactory::$database;
		$this->savedFactoryState['mailer']      = is_object(JoomlaFactory::$mailer) ? clone JoomlaFactory::$mailer : JoomlaFactory::$mailer;
	}

	/**
	 * Sets the Factory pointers
	 *
	 * @return  void
	 */
	protected function restoreFactoryState()
	{
		JoomlaFactory::$application = $this->savedFactoryState['application'];
		JoomlaFactory::$config      = $this->savedFactoryState['config'];
		JoomlaFactory::$dates       = $this->savedFactoryState['dates'];
		JoomlaFactory::$session     = $this->savedFactoryState['session'];
		JoomlaFactory::$language    = $this->savedFactoryState['language'];
		JoomlaFactory::$document    = $this->savedFactoryState['document'];
		JoomlaFactory::$acl         = $this->savedFactoryState['acl'];
		JoomlaFactory::$database    = $this->savedFactoryState['database'];
		JoomlaFactory::$mailer      = $this->savedFactoryState['mailer'];
	}

	/**
	 * Normalizes two arrays containing lists of fields:
	 * * Converts utf8mb4 references to their utf8 equivalents
	 * * Converts null to empty strings (since the null/empty string result is db version dependent)
	 *
	 * @param                  $fields
	 * @param \JDatabaseDriver $db
	 *
	 * @return array
	 */
	protected function _normalizeTableFields($fields, \JDatabaseDriver $db)
	{
		if (!is_array($fields))
		{
			return $fields;
		}

		$ret = [];

		foreach ($fields as $fieldName => $def)
		{
			$def = (array) $def;

			$def = array_map(function ($value) {
				if (is_null($value))
				{
					return '';
				}

				if (!is_numeric($value) && is_string($value))
				{
					$value = str_replace('utf8mb4_', 'utf8_', $value);
					$value = str_replace('_unicode_ci', '_general_ci', $value);
				}

				return $value;
			}, $def);

			$ret[$fieldName] = (object) $def;
		}

		return $ret;
	}
}
