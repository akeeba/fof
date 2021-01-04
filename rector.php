<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$parameters = $containerConfigurator->parameters();

	// Custom autoloader for our classes
	$parameters->set(Option::AUTOLOAD_PATHS, [
		__DIR__ . '/build/autoloader_for_rector.php',
	]);

	// Use PHP 7.2 features only
	$parameters->set(Option::PHP_VERSION_FEATURES, '7.2');

	//Paths to include
	$parameters->set(Option::PATHS, [
		__DIR__ . '/fof',
	]);

	/**
	 * Paths to exclude from automatic refactoring
	 *
	 * WARNING! Rector includes .php files. Any terminal endpoint file will KILL Rector.
	 *
	 * This means that all CLI scripts and error pages MUST be excluded.
	 *
	 * @see https://github.com/rectorphp/rector/blob/master/docs/how_to_ignore_rule_or_paths.md
	 */
	$parameters->set(Option::SKIP, [
		__DIR__ . '/fof/Cli/*',
		__DIR__ . '/fof/language/*',
		__DIR__ . '/fof/sql/*',
		__DIR__ . '/fof/ViewTemplates/*',
		__DIR__ . '/fof/*.xml',
		__DIR__ . '/fof/*.txt',
		__DIR__ . '/fof/.htaccess',
		__DIR__ . '/fof/web.config',
		__DIR__ . '/plugins/user/foftoken/.htaccess',
		__DIR__ . '/plugins/user/foftoken/web.config',
		__DIR__ . '/plugins/user/foftoken/language/*',

		// WATCH OUT! This does crazy things, like convert $ret['ErrorException'] to $ret[\ErrorException::class] which
		// is unfortunate and messes everything up.
		StringClassNameToClassConstantRector::class,

		AddDefaultValueForUndefinedVariableRector::class,
	]);

	// Rector sets to apply, see vendor/rector/rector/config/set
	$parameters->set(Option::SETS, [
		SetList::EARLY_RETURN,
//		SetList::CODE_QUALITY,
//		SetList::PHP_52,
//		SetList::PHP_53,
//		SetList::PHP_54,
//		SetList::PHP_55,
//		SetList::PHP_56,
//		SetList::PHP_70,
//		SetList::PHP_71,
//		SetList::PHP_72,
	]);

	$services = $containerConfigurator->services();

	// Joomla 3.9 compatibility
	$services->set(RenameClassRector::class)
		->call('configure', [
			[
				RenameClassRector::OLD_TO_NEW_CLASSES => [
					'JRegistry'                         => 'Joomla\Registry\Registry',
					'JRegistryFormat'                   => 'Joomla\Registry\AbstractRegistryFormat',
					'JRegistryFormatINI'                => 'Joomla\Registry\Format\Ini',
					'JRegistryFormatJSON'               => 'Joomla\Registry\Format\Json',
					'JRegistryFormatPHP'                => 'Joomla\Registry\Format\Php',
					'JRegistryFormatXML'                => 'Joomla\Registry\Format\Xml',
					'JStringInflector'                  => 'Joomla\String\Inflector',
					'JStringNormalise'                  => 'Joomla\String\Normalise',
					'JRegistryFormatIni'                => 'Joomla\Registry\Format\Ini',
					'JRegistryFormatJson'               => 'Joomla\Registry\Format\Json',
					'JRegistryFormatPhp'                => 'Joomla\Registry\Format\Php',
					'JRegistryFormatXml'                => 'Joomla\Registry\Format\Xml',
					'JApplicationWebClient'             => 'Joomla\Application\Web\WebClient',
					'JData'                             => 'Joomla\Data\DataObject',
					'JDataSet'                          => 'Joomla\Data\DataSet',
					'JDataDumpable'                     => 'Joomla\Data\DumpableInterface',
					'JApplicationAdministrator'         => 'Joomla\CMS\Application\AdministratorApplication',
					'JApplicationHelper'                => 'Joomla\CMS\Application\ApplicationHelper',
					'JApplicationBase'                  => 'Joomla\CMS\Application\BaseApplication',
					'JApplicationCli'                   => 'Joomla\CMS\Application\CliApplication',
					'JApplicationCms'                   => 'Joomla\CMS\Application\CMSApplication',
					'JApplicationDaemon'                => 'Joomla\CMS\Application\DaemonApplication',
					'JApplicationSite'                  => 'Joomla\CMS\Application\SiteApplication',
					'JApplicationWeb'                   => 'Joomla\CMS\Application\WebApplication',
					'JDaemon'                           => 'Joomla\CMS\Application\DaemonApplication',
					'JCli'                              => 'Joomla\CMS\Application\CliApplication',
					'JWeb'                              => 'Joomla\CMS\Application\WebApplication',
					'JWebClient'                        => 'Joomla\Application\Web\WebClient',
					'JModelAdmin'                       => 'Joomla\CMS\MVC\Model\AdminModel',
					'JModelForm'                        => 'Joomla\CMS\MVC\Model\FormModel',
					'JModelItem'                        => 'Joomla\CMS\MVC\Model\ItemModel',
					'JModelList'                        => 'Joomla\CMS\MVC\Model\ListModel',
					'JModelLegacy'                      => 'Joomla\CMS\MVC\Model\BaseDatabaseModel',
					'JViewCategories'                   => 'Joomla\CMS\MVC\View\CategoriesView',
					'JViewCategory'                     => 'Joomla\CMS\MVC\View\CategoryView',
					'JViewCategoryfeed'                 => 'Joomla\CMS\MVC\View\CategoryFeedView',
					'JViewLegacy'                       => 'Joomla\CMS\MVC\View\HtmlView',
					'JControllerAdmin'                  => 'Joomla\CMS\MVC\Controller\AdminController',
					'JControllerLegacy'                 => 'Joomla\CMS\MVC\Controller\BaseController',
					'JControllerForm'                   => 'Joomla\CMS\MVC\Controller\FormController',
					'JTableInterface'                   => 'Joomla\CMS\Table\TableInterface',
					'JTable'                            => 'Joomla\CMS\Table\Table',
					'JTableNested'                      => 'Joomla\CMS\Table\Nested',
					'JTableAsset'                       => 'Joomla\CMS\Table\Asset',
					'JTableExtension'                   => 'Joomla\CMS\Table\Extension',
					'JTableLanguage'                    => 'Joomla\CMS\Table\Language',
					'JTableUpdate'                      => 'Joomla\CMS\Table\Update',
					'JTableUpdatesite'                  => 'Joomla\CMS\Table\UpdateSite',
					'JTableUser'                        => 'Joomla\CMS\Table\User',
					'JTableUsergroup'                   => 'Joomla\CMS\Table\Usergroup',
					'JTableViewlevel'                   => 'Joomla\CMS\Table\ViewLevel',
					'JTableContenthistory'              => 'Joomla\CMS\Table\ContentHistory',
					'JTableContenttype'                 => 'Joomla\CMS\Table\ContentType',
					'JTableCorecontent'                 => 'Joomla\CMS\Table\CoreContent',
					'JTableUcm'                         => 'Joomla\CMS\Table\Ucm',
					'JTableCategory'                    => 'Joomla\CMS\Table\Category',
					'JTableContent'                     => 'Joomla\CMS\Table\Content',
					'JTableMenu'                        => 'Joomla\CMS\Table\Menu',
					'JTableMenuType'                    => 'Joomla\CMS\Table\MenuType',
					'JTableModule'                      => 'Joomla\CMS\Table\Module',
					'JTableObserver'                    => 'Joomla\CMS\Table\Observer\AbstractObserver',
					'JTableObserverContenthistory'      => 'Joomla\CMS\Table\Observer\ContentHistory',
					'JTableObserverTags'                => 'Joomla\CMS\Table\Observer\Tags',
					'JAccess'                           => 'Joomla\CMS\Access\Access',
					'JAccessRule'                       => 'Joomla\CMS\Access\Rule',
					'JAccessRules'                      => 'Joomla\CMS\Access\Rules',
					'JAccessWrapperAccess'              => 'Joomla\CMS\Access\Wrapper\Access',
					'JAccessExceptionNotallowed'        => 'Joomla\CMS\Access\Exception\NotAllowed',
					'JRule'                             => 'Joomla\CMS\Access\Rule',
					'JRules'                            => 'Joomla\CMS\Access\Rules',
					'JHelp'                             => 'Joomla\CMS\Help\Help',
					'JCaptcha'                          => 'Joomla\CMS\Captcha\Captcha',
					'JLanguageAssociations'             => 'Joomla\CMS\Language\Associations',
					'JLanguage'                         => 'Joomla\CMS\Language\Language',
					'JLanguageHelper'                   => 'Joomla\CMS\Language\LanguageHelper',
					'JLanguageStemmer'                  => 'Joomla\CMS\Language\LanguageStemmer',
					'JLanguageMultilang'                => 'Joomla\CMS\Language\Multilanguage',
					'JText'                             => 'Joomla\CMS\Language\Text',
					'JLanguageTransliterate'            => 'Joomla\CMS\Language\Transliterate',
					'JLanguageStemmerPorteren'          => 'Joomla\CMS\Language\Stemmer\Porteren',
					'JLanguageWrapperText'              => 'Joomla\CMS\Language\Wrapper\JTextWrapper',
					'JLanguageWrapperHelper'            => 'Joomla\CMS\Language\Wrapper\LanguageHelperWrapper',
					'JLanguageWrapperTransliterate'     => 'Joomla\CMS\Language\Wrapper\TransliterateWrapper',
					'JComponentHelper'                  => 'Joomla\CMS\Component\ComponentHelper',
					'JComponentRecord'                  => 'Joomla\CMS\Component\ComponentRecord',
					'JComponentExceptionMissing'        => 'Joomla\CMS\Component\Exception\MissingComponentException',
					'JComponentRouterBase'              => 'Joomla\CMS\Component\Router\RouterBase',
					'JComponentRouterInterface'         => 'Joomla\CMS\Component\Router\RouterInterface',
					'JComponentRouterLegacy'            => 'Joomla\CMS\Component\Router\RouterLegacy',
					'JComponentRouterView'              => 'Joomla\CMS\Component\Router\RouterView',
					'JComponentRouterViewconfiguration' => 'Joomla\CMS\Component\Router\RouterViewConfiguration',
					'JComponentRouterRulesMenu'         => 'Joomla\CMS\Component\Router\Rules\MenuRules',
					'JComponentRouterRulesNomenu'       => 'Joomla\CMS\Component\Router\Rules\NomenuRules',
					'JComponentRouterRulesInterface'    => 'Joomla\CMS\Component\Router\Rules\RulesInterface',
					'JComponentRouterRulesStandard'     => 'Joomla\CMS\Component\Router\Rules\StandardRules',
					'JEditor'                           => 'Joomla\CMS\Editor\Editor',
					'JErrorPage'                        => 'Joomla\CMS\Exception\ExceptionHandler',
					'JAuthenticationHelper'             => 'Joomla\CMS\Helper\AuthenticationHelper',
					'JHelper'                           => 'Joomla\CMS\Helper\CMSHelper',
					'JHelperContent'                    => 'Joomla\CMS\Helper\ContentHelper',
					'JHelperContenthistory'             => 'Joomla\CMS\Helper\ContentHistoryHelper',
					'JLibraryHelper'                    => 'Joomla\CMS\Helper\LibraryHelper',
					'JHelperMedia'                      => 'Joomla\CMS\Helper\MediaHelper',
					'JModuleHelper'                     => 'Joomla\CMS\Helper\ModuleHelper',
					'JHelperRoute'                      => 'Joomla\CMS\Helper\RouteHelper',
					'JSearchHelper'                     => 'Joomla\CMS\Helper\SearchHelper',
					'JHelperTags'                       => 'Joomla\CMS\Helper\TagsHelper',
					'JHelperUsergroups'                 => 'Joomla\CMS\Helper\UserGroupsHelper',
					'JLayoutBase'                       => 'Joomla\CMS\Layout\BaseLayout',
					'JLayoutFile'                       => 'Joomla\CMS\Layout\FileLayout',
					'JLayoutHelper'                     => 'Joomla\CMS\Layout\LayoutHelper',
					'JLayout'                           => 'Joomla\CMS\Layout\LayoutInterface',
					'JResponseJson'                     => 'Joomla\CMS\Response\JsonResponse',
					'JPlugin'                           => 'Joomla\CMS\Plugin\CMSPlugin',
					'JPluginHelper'                     => 'Joomla\CMS\Plugin\PluginHelper',
					'JMenu'                             => 'Joomla\CMS\Menu\AbstractMenu',
					'JMenuAdministrator'                => 'Joomla\CMS\Menu\AdministratorMenu',
					'JMenuItem'                         => 'Joomla\CMS\Menu\MenuItem',
					'JMenuSite'                         => 'Joomla\CMS\Menu\SiteMenu',
					'JPagination'                       => 'Joomla\CMS\Pagination\Pagination',
					'JPaginationObject'                 => 'Joomla\CMS\Pagination\PaginationObject',
					'JPathway'                          => 'Joomla\CMS\Pathway\Pathway',
					'JPathwaySite'                      => 'Joomla\CMS\Pathway\SitePathway',
					'JSchemaChangeitem'                 => 'Joomla\CMS\Schema\ChangeItem',
					'JSchemaChangeset'                  => 'Joomla\CMS\Schema\ChangeSet',
					'JSchemaChangeitemMysql'            => 'Joomla\CMS\Schema\ChangeItem\MysqlChangeItem',
					'JSchemaChangeitemPostgresql'       => 'Joomla\CMS\Schema\ChangeItem\PostgresqlChangeItem',
					'JSchemaChangeitemSqlsrv'           => 'Joomla\CMS\Schema\ChangeItem\SqlsrvChangeItem',
					'JUcm'                              => 'Joomla\CMS\UCM\UCM', 'JUcmBase' => 'Joomla\CMS\UCM\UCMBase',
					'JUcmContent'                       => 'Joomla\CMS\UCM\UCMContent',
					'JUcmType'                          => 'Joomla\CMS\UCM\UCMType',
					'JToolbar'                          => 'Joomla\CMS\Toolbar\Toolbar',
					'JToolBar'                          => 'Joomla\CMS\Toolbar\Toolbar',
					'JToolbarButton'                    => 'Joomla\CMS\Toolbar\ToolbarButton',
					'JToolbarButtonConfirm'             => 'Joomla\CMS\Toolbar\Button\ConfirmButton',
					'JToolbarButtonCustom'              => 'Joomla\CMS\Toolbar\Button\CustomButton',
					'JToolbarButtonHelp'                => 'Joomla\CMS\Toolbar\Button\HelpButton',
					'JToolbarButtonLink'                => 'Joomla\CMS\Toolbar\Button\LinkButton',
					'JToolbarButtonPopup'               => 'Joomla\CMS\Toolbar\Button\PopupButton',
					'JToolbarButtonSeparator'           => 'Joomla\CMS\Toolbar\Button\SeparatorButton',
					'JToolbarButtonSlider'              => 'Joomla\CMS\Toolbar\Button\SliderButton',
					'JToolbarButtonStandard'            => 'Joomla\CMS\Toolbar\Button\StandardButton',
					'JButton'                           => 'Joomla\CMS\Toolbar\ToolbarButton',
					'JVersion'                          => 'Joomla\CMS\Version',
					'JAuthentication'                   => 'Joomla\CMS\Authentication\Authentication',
					'JAuthenticationResponse'           => 'Joomla\CMS\Authentication\AuthenticationResponse',
					'JBrowser'                          => 'Joomla\CMS\Environment\Browser',
					'JAssociationExtensionInterface'    => 'Joomla\CMS\Association\AssociationExtensionInterface',
					'JAssociationExtensionHelper'       => 'Joomla\CMS\Association\AssociationExtensionHelper',
					'JDocument'                         => 'Joomla\CMS\Document\Document',
					'JDocumentError'                    => 'Joomla\CMS\Document\ErrorDocument',
					'JDocumentFeed'                     => 'Joomla\CMS\Document\FeedDocument',
					'JDocumentHtml'                     => 'Joomla\CMS\Document\HtmlDocument',
					'JDocumentImage'                    => 'Joomla\CMS\Document\ImageDocument',
					'JDocumentJson'                     => 'Joomla\CMS\Document\JsonDocument',
					'JDocumentOpensearch'               => 'Joomla\CMS\Document\OpensearchDocument',
					'JDocumentRaw'                      => 'Joomla\CMS\Document\RawDocument',
					'JDocumentRenderer'                 => 'Joomla\CMS\Document\DocumentRenderer',
					'JDocumentXml'                      => 'Joomla\CMS\Document\XmlDocument',
					'JDocumentRendererFeedAtom'         => 'Joomla\CMS\Document\Renderer\Feed\AtomRenderer',
					'JDocumentRendererFeedRss'          => 'Joomla\CMS\Document\Renderer\Feed\RssRenderer',
					'JDocumentRendererHtmlComponent'    => 'Joomla\CMS\Document\Renderer\Html\ComponentRenderer',
					'JDocumentRendererHtmlHead'         => 'Joomla\CMS\Document\Renderer\Html\HeadRenderer',
					'JDocumentRendererHtmlMessage'      => 'Joomla\CMS\Document\Renderer\Html\MessageRenderer',
					'JDocumentRendererHtmlModule'       => 'Joomla\CMS\Document\Renderer\Html\ModuleRenderer',
					'JDocumentRendererHtmlModules'      => 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer',
					'JDocumentRendererAtom'             => 'Joomla\CMS\Document\Renderer\Feed\AtomRenderer',
					'JDocumentRendererRSS'              => 'Joomla\CMS\Document\Renderer\Feed\RssRenderer',
					'JDocumentRendererComponent'        => 'Joomla\CMS\Document\Renderer\Html\ComponentRenderer',
					'JDocumentRendererHead'             => 'Joomla\CMS\Document\Renderer\Html\HeadRenderer',
					'JDocumentRendererMessage'          => 'Joomla\CMS\Document\Renderer\Html\MessageRenderer',
					'JDocumentRendererModule'           => 'Joomla\CMS\Document\Renderer\Html\ModuleRenderer',
					'JDocumentRendererModules'          => 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer',
					'JFeedEnclosure'                    => 'Joomla\CMS\Document\Feed\FeedEnclosure',
					'JFeedImage'                        => 'Joomla\CMS\Document\Feed\FeedImage',
					'JFeedItem'                         => 'Joomla\CMS\Document\Feed\FeedItem',
					'JOpenSearchImage'                  => 'Joomla\CMS\Document\Opensearch\OpensearchImage',
					'JOpenSearchUrl'                    => 'Joomla\CMS\Document\Opensearch\OpensearchUrl',
					'JFilterInput'                      => 'Joomla\CMS\Filter\InputFilter',
					'JFilterOutput'                     => 'Joomla\CMS\Filter\OutputFilter',
					'JFilterWrapperOutput'              => 'Joomla\CMS\Filter\Wrapper\OutputFilterWrapper',
					'JHttp'                             => 'Joomla\CMS\Http\Http',
					'JHttpFactory'                      => 'Joomla\CMS\Http\HttpFactory',
					'JHttpResponse'                     => 'Joomla\CMS\Http\Response',
					'JHttpTransport'                    => 'Joomla\CMS\Http\TransportInterface',
					'JHttpTransportCurl'                => 'Joomla\CMS\Http\Transport\CurlTransport',
					'JHttpTransportSocket'              => 'Joomla\CMS\Http\Transport\SocketTransport',
					'JHttpTransportStream'              => 'Joomla\CMS\Http\Transport\StreamTransport',
					'JHttpWrapperFactory'               => 'Joomla\CMS\Http\Wrapper\FactoryWrapper',
					'JInstaller'                        => 'Joomla\CMS\Installer\Installer',
					'JInstallerAdapter'                 => 'Joomla\CMS\Installer\InstallerAdapter',
					'JInstallerExtension'               => 'Joomla\CMS\Installer\InstallerExtension',
					'JExtension'                        => 'Joomla\CMS\Installer\InstallerExtension',
					'JInstallerHelper'                  => 'Joomla\CMS\Installer\InstallerHelper',
					'JInstallerScript'                  => 'Joomla\CMS\Installer\InstallerScript',
					'JInstallerManifest'                => 'Joomla\CMS\Installer\Manifest',
					'JInstallerAdapterComponent'        => 'Joomla\CMS\Installer\Adapter\ComponentAdapter',
					'JInstallerComponent'               => 'Joomla\CMS\Installer\Adapter\ComponentAdapter',
					'JInstallerAdapterFile'             => 'Joomla\CMS\Installer\Adapter\FileAdapter',
					'JInstallerFile'                    => 'Joomla\CMS\Installer\Adapter\FileAdapter',
					'JInstallerAdapterLanguage'         => 'Joomla\CMS\Installer\Adapter\LanguageAdapter',
					'JInstallerLanguage'                => 'Joomla\CMS\Installer\Adapter\LanguageAdapter',
					'JInstallerAdapterLibrary'          => 'Joomla\CMS\Installer\Adapter\LibraryAdapter',
					'JInstallerLibrary'                 => 'Joomla\CMS\Installer\Adapter\LibraryAdapter',
					'JInstallerAdapterModule'           => 'Joomla\CMS\Installer\Adapter\ModuleAdapter',
					'JInstallerModule'                  => 'Joomla\CMS\Installer\Adapter\ModuleAdapter',
					'JInstallerAdapterPackage'          => 'Joomla\CMS\Installer\Adapter\PackageAdapter',
					'JInstallerPackage'                 => 'Joomla\CMS\Installer\Adapter\PackageAdapter',
					'JInstallerAdapterPlugin'           => 'Joomla\CMS\Installer\Adapter\PluginAdapter',
					'JInstallerPlugin'                  => 'Joomla\CMS\Installer\Adapter\PluginAdapter',
					'JInstallerAdapterTemplate'         => 'Joomla\CMS\Installer\Adapter\TemplateAdapter',
					'JInstallerTemplate'                => 'Joomla\CMS\Installer\Adapter\TemplateAdapter',
					'JInstallerManifestLibrary'         => 'Joomla\CMS\Installer\Manifest\LibraryManifest',
					'JInstallerManifestPackage'         => 'Joomla\CMS\Installer\Manifest\PackageManifest',
					'JRouterAdministrator'              => 'Joomla\CMS\Router\AdministratorRouter',
					'JRoute'                            => 'Joomla\CMS\Router\Route',
					'JRouter'                           => 'Joomla\CMS\Router\Router',
					'JRouterSite'                       => 'Joomla\CMS\Router\SiteRouter',
					'JCategories'                       => 'Joomla\CMS\Categories\Categories',
					'JCategoryNode'                     => 'Joomla\CMS\Categories\CategoryNode',
					'JDate'                             => 'Joomla\CMS\Date\Date', 'JLog' => 'Joomla\CMS\Log\Log',
					'JLogEntry'                         => 'Joomla\CMS\Log\LogEntry',
					'JLogLogger'                        => 'Joomla\CMS\Log\Logger',
					'JLogger'                           => 'Joomla\CMS\Log\Logger',
					'JLogLoggerCallback'                => 'Joomla\CMS\Log\Logger\CallbackLogger',
					'JLogLoggerDatabase'                => 'Joomla\CMS\Log\Logger\DatabaseLogger',
					'JLogLoggerEcho'                    => 'Joomla\CMS\Log\Logger\EchoLogger',
					'JLogLoggerFormattedtext'           => 'Joomla\CMS\Log\Logger\FormattedtextLogger',
					'JLogLoggerMessagequeue'            => 'Joomla\CMS\Log\Logger\MessagequeueLogger',
					'JLogLoggerSyslog'                  => 'Joomla\CMS\Log\Logger\SyslogLogger',
					'JLogLoggerW3c'                     => 'Joomla\CMS\Log\Logger\W3cLogger',
					'JProfiler'                         => 'Joomla\CMS\Profiler\Profiler',
					'JUri'                              => 'Joomla\CMS\Uri\Uri',
					'JCache'                            => 'Joomla\CMS\Cache\Cache',
					'JCacheController'                  => 'Joomla\CMS\Cache\CacheController',
					'JCacheStorage'                     => 'Joomla\CMS\Cache\CacheStorage',
					'JCacheControllerCallback'          => 'Joomla\CMS\Cache\Controller\CallbackController',
					'JCacheControllerOutput'            => 'Joomla\CMS\Cache\Controller\OutputController',
					'JCacheControllerPage'              => 'Joomla\CMS\Cache\Controller\PageController',
					'JCacheControllerView'              => 'Joomla\CMS\Cache\Controller\ViewController',
					'JCacheStorageApc'                  => 'Joomla\CMS\Cache\Storage\ApcStorage',
					'JCacheStorageApcu'                 => 'Joomla\CMS\Cache\Storage\ApcuStorage',
					'JCacheStorageHelper'               => 'Joomla\CMS\Cache\Storage\CacheStorageHelper',
					'JCacheStorageCachelite'            => 'Joomla\CMS\Cache\Storage\CacheliteStorage',
					'JCacheStorageFile'                 => 'Joomla\CMS\Cache\Storage\FileStorage',
					'JCacheStorageMemcached'            => 'Joomla\CMS\Cache\Storage\MemcachedStorage',
					'JCacheStorageMemcache'             => 'Joomla\CMS\Cache\Storage\MemcacheStorage',
					'JCacheStorageRedis'                => 'Joomla\CMS\Cache\Storage\RedisStorage',
					'JCacheStorageWincache'             => 'Joomla\CMS\Cache\Storage\WincacheStorage',
					'JCacheStorageXcache'               => 'Joomla\CMS\Cache\Storage\XcacheStorage',
					'JCacheException'                   => 'Joomla\CMS\Cache\Exception\CacheExceptionInterface',
					'JCacheExceptionConnecting'         => 'Joomla\CMS\Cache\Exception\CacheConnectingException',
					'JCacheExceptionUnsupported'        => 'Joomla\CMS\Cache\Exception\UnsupportedCacheException',
					'JSession'                          => 'Joomla\CMS\Session\Session',
					'JSessionExceptionUnsupported'      => 'Joomla\CMS\Session\Exception\UnsupportedStorageException',
					'JUser'                             => 'Joomla\CMS\User\User',
					'JUserHelper'                       => 'Joomla\CMS\User\UserHelper',
					'JUserWrapperHelper'                => 'Joomla\CMS\User\UserWrapper',
					'JForm'                             => 'Joomla\CMS\Form\Form',
					'JFormField'                        => 'Joomla\CMS\Form\FormField',
					'JFormHelper'                       => 'Joomla\CMS\Form\FormHelper',
					'JFormRule'                         => 'Joomla\CMS\Form\FormRule',
					'JFormWrapper'                      => 'Joomla\CMS\Form\FormWrapper',
					'JFormFieldAuthor'                  => 'Joomla\CMS\Form\Field\AuthorField',
					'JFormFieldCaptcha'                 => 'Joomla\CMS\Form\Field\CaptchaField',
					'JFormFieldChromeStyle'             => 'Joomla\CMS\Form\Field\ChromestyleField',
					'JFormFieldContenthistory'          => 'Joomla\CMS\Form\Field\ContenthistoryField',
					'JFormFieldContentlanguage'         => 'Joomla\CMS\Form\Field\ContentlanguageField',
					'JFormFieldContenttype'             => 'Joomla\CMS\Form\Field\ContenttypeField',
					'JFormFieldEditor'                  => 'Joomla\CMS\Form\Field\EditorField',
					'JFormFieldFrontend_Language'       => 'Joomla\CMS\Form\Field\FrontendlanguageField',
					'JFormFieldHeadertag'               => 'Joomla\CMS\Form\Field\HeadertagField',
					'JFormFieldHelpsite'                => 'Joomla\CMS\Form\Field\HelpsiteField',
					'JFormFieldLastvisitDateRange'      => 'Joomla\CMS\Form\Field\LastvisitdaterangeField',
					'JFormFieldLimitbox'                => 'Joomla\CMS\Form\Field\LimitboxField',
					'JFormFieldMedia'                   => 'Joomla\CMS\Form\Field\MediaField',
					'JFormFieldMenu'                    => 'Joomla\CMS\Form\Field\MenuField',
					'JFormFieldMenuitem'                => 'Joomla\CMS\Form\Field\MenuitemField',
					'JFormFieldModuleOrder'             => 'Joomla\CMS\Form\Field\ModuleorderField',
					'JFormFieldModulePosition'          => 'Joomla\CMS\Form\Field\ModulepositionField',
					'JFormFieldModuletag'               => 'Joomla\CMS\Form\Field\ModuletagField',
					'JFormFieldOrdering'                => 'Joomla\CMS\Form\Field\OrderingField',
					'JFormFieldPlugin_Status'           => 'Joomla\CMS\Form\Field\PluginstatusField',
					'JFormFieldRedirect_Status'         => 'Joomla\CMS\Form\Field\RedirectStatusField',
					'JFormFieldRegistrationDateRange'   => 'Joomla\CMS\Form\Field\RegistrationdaterangeField',
					'JFormFieldStatus'                  => 'Joomla\CMS\Form\Field\StatusField',
					'JFormFieldTag'                     => 'Joomla\CMS\Form\Field\TagField',
					'JFormFieldTemplatestyle'           => 'Joomla\CMS\Form\Field\TemplatestyleField',
					'JFormFieldUserActive'              => 'Joomla\CMS\Form\Field\UseractiveField',
					'JFormFieldUserGroupList'           => 'Joomla\CMS\Form\Field\UsergrouplistField',
					'JFormFieldUserState'               => 'Joomla\CMS\Form\Field\UserstateField',
					'JFormFieldUser'                    => 'Joomla\CMS\Form\Field\UserField',
					'JFormRuleBoolean'                  => 'Joomla\CMS\Form\Rule\BooleanRule',
					'JFormRuleCalendar'                 => 'Joomla\CMS\Form\Rule\CalendarRule',
					'JFormRuleCaptcha'                  => 'Joomla\CMS\Form\Rule\CaptchaRule',
					'JFormRuleColor'                    => 'Joomla\CMS\Form\Rule\ColorRule',
					'JFormRuleEmail'                    => 'Joomla\CMS\Form\Rule\EmailRule',
					'JFormRuleEquals'                   => 'Joomla\CMS\Form\Rule\EqualsRule',
					'JFormRuleNotequals'                => 'Joomla\CMS\Form\Rule\NotequalsRule',
					'JFormRuleNumber'                   => 'Joomla\CMS\Form\Rule\NumberRule',
					'JFormRuleOptions'                  => 'Joomla\CMS\Form\Rule\OptionsRule',
					'JFormRulePassword'                 => 'Joomla\CMS\Form\Rule\PasswordRule',
					'JFormRuleRules'                    => 'Joomla\CMS\Form\Rule\RulesRule',
					'JFormRuleTel'                      => 'Joomla\CMS\Form\Rule\TelRule',
					'JFormRuleUrl'                      => 'Joomla\CMS\Form\Rule\UrlRule',
					'JFormRuleUsername'                 => 'Joomla\CMS\Form\Rule\UsernameRule',
					'JMicrodata'                        => 'Joomla\CMS\Microdata\Microdata',
					'JFactory'                          => 'Joomla\CMS\Factory',
					'JMail'                             => 'Joomla\CMS\Mail\Mail',
					'JMailHelper'                       => 'Joomla\CMS\Mail\MailHelper',
					'JMailWrapperHelper'                => 'Joomla\CMS\Mail\MailWrapper',
					'JClientHelper'                     => 'Joomla\CMS\Client\ClientHelper',
					'JClientWrapperHelper'              => 'Joomla\CMS\Client\ClientWrapper',
					'JClientFtp'                        => 'Joomla\CMS\Client\FtpClient',
					'JFTP'                              => 'Joomla\CMS\Client\FtpClient',
					'JClientLdap'                       => 'Joomla\Ldap\LdapClient',
					'JLDAP'                             => 'Joomla\Ldap\LdapClient',
					'JUpdate'                           => 'Joomla\CMS\Updater\Update',
					'JUpdateAdapter'                    => 'Joomla\CMS\Updater\UpdateAdapter',
					'JUpdater'                          => 'Joomla\CMS\Updater\Updater',
					'JUpdaterCollection'                => 'Joomla\CMS\Updater\Adapter\CollectionAdapter',
					'JUpdaterExtension'                 => 'Joomla\CMS\Updater\Adapter\ExtensionAdapter',
					'JCrypt'                            => 'Joomla\CMS\Crypt\Crypt',
					'JCryptCipher'                      => 'Joomla\CMS\Crypt\CipherInterface',
					'JCryptKey'                         => 'Joomla\CMS\Crypt\Key',
					'JCryptPassword'                    => 'Joomla\CMS\Crypt\CryptPassword',
					'JCryptCipherBlowfish'              => 'Joomla\CMS\Crypt\Cipher\BlowfishCipher',
					'JCryptCipherCrypto'                => 'Joomla\CMS\Crypt\Cipher\CryptoCipher',
					'JCryptCipherMcrypt'                => 'Joomla\CMS\Crypt\Cipher\McryptCipher',
					'JCryptCipherRijndael256'           => 'Joomla\CMS\Crypt\Cipher\Rijndael256Cipher',
					'JCryptCipherSimple'                => 'Joomla\CMS\Crypt\Cipher\SimpleCipher',
					'JCryptCipher3Des'                  => 'Joomla\CMS\Crypt\Cipher\TripleDesCipher',
					'JCryptPasswordSimple'              => 'Joomla\CMS\Crypt\Password\SimpleCryptPassword',
					'JStringPunycode'                   => 'Joomla\CMS\String\PunycodeHelper',
					'JBuffer'                           => 'Joomla\CMS\Utility\BufferStreamHandler',
					'JUtility'                          => 'Joomla\CMS\Utility\Utility',
					'JInputCli'                         => 'Joomla\CMS\Input\Cli',
					'JInputCookie'                      => 'Joomla\CMS\Input\Cookie',
					'JInputFiles'                       => 'Joomla\CMS\Input\Files',
					'JInput'                            => 'Joomla\CMS\Input\Input',
					'JInputJSON'                        => 'Joomla\CMS\Input\Json',
					'JFeed'                             => 'Joomla\CMS\Feed\Feed',
					'JFeedEntry'                        => 'Joomla\CMS\Feed\FeedEntry',
					'JFeedFactory'                      => 'Joomla\CMS\Feed\FeedFactory',
					'JFeedLink'                         => 'Joomla\CMS\Feed\FeedLink',
					'JFeedParser'                       => 'Joomla\CMS\Feed\FeedParser',
					'JFeedPerson'                       => 'Joomla\CMS\Feed\FeedPerson',
					'JFeedParserAtom'                   => 'Joomla\CMS\Feed\Parser\AtomParser',
					'JFeedParserNamespace'              => 'Joomla\CMS\Feed\Parser\NamespaceParserInterface',
					'JFeedParserRss'                    => 'Joomla\CMS\Feed\Parser\RssParser',
					'JFeedParserRssItunes'              => 'Joomla\CMS\Feed\Parser\Rss\ItunesRssParser',
					'JFeedParserRssMedia'               => 'Joomla\CMS\Feed\Parser\Rss\MediaRssParser',
					'JImage'                            => 'Joomla\CMS\Image\Image',
					'JImageFilter'                      => 'Joomla\CMS\Image\ImageFilter',
					'JImageFilterBackgroundfill'        => 'Joomla\Image\Filter\Backgroundfill',
					'JImageFilterBrightness'            => 'Joomla\Image\Filter\Brightness',
					'JImageFilterContrast'              => 'Joomla\Image\Filter\Contrast',
					'JImageFilterEdgedetect'            => 'Joomla\Image\Filter\Edgedetect',
					'JImageFilterEmboss'                => 'Joomla\Image\Filter\Emboss',
					'JImageFilterNegate'                => 'Joomla\Image\Filter\Negate',
					'JImageFilterSketchy'               => 'Joomla\Image\Filter\Sketchy',
					'JImageFilterSmooth'                => 'Joomla\Image\Filter\Smooth',
					'JObject'                           => 'Joomla\CMS\Object\CMSObject',
					'JExtensionHelper'                  => 'Joomla\CMS\Extension\ExtensionHelper',
					'JHtml'                             => 'Joomla\CMS\HTML\HTMLHelper',
					'JToolbarHelper'                    => 'Joomla\CMS\Toolbar\ToolbarHelper',
					'JCryptCipherSodium'                => 'Joomla\CMS\Crypt\Cipher\SodiumCipher',
					'JFile'                             => 'Joomla\CMS\Filesystem\File',
					'JFolder'                           => 'Joomla\CMS\Filesystem\Folder',
					'JFilesystemHelper'                 => 'Joomla\CMS\Filesystem\FilesystemHelper',
					'JFilesystemPatcher'                => 'Joomla\CMS\Filesystem\Patcher',
					'JPath'                             => 'Joomla\CMS\Filesystem\Path',
					'JStream'                           => 'Joomla\CMS\Filesystem\Stream',
					'JStreamString'                     => 'Joomla\CMS\Filesystem\Streams\StreamString',
					'JStringController'                 => 'Joomla\CMS\Filesystem\Support\StringController',
					'JFilesystemWrapperFile'            => 'Joomla\CMS\Filesystem\Wrapper\FileWrapper',
					'JFilesystemWrapperFolder'          => 'Joomla\CMS\Filesystem\Wrapper\FolderWrapper',
					'JFilesystemWrapperPath'            => 'Joomla\CMS\Filesystem\Wrapper\PathWrapper',
				],
			],
		]);
};
