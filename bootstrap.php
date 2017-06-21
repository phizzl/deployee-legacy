<?php

use MySQLHandler\MySQLHandler;
use Phizzl\Deployee\Configuration\ConfigMerger;
use Phizzl\Deployee\Configuration\Configuration;
use Phizzl\Deployee\Configuration\ConfigurationInterface;
use Phizzl\Deployee\Configuration\YamlConfigurationLoader;
use Phizzl\Deployee\Db\Db;
use Phizzl\Deployee\Deployments\Dispatcher\DeploymentDispatchValidator;
use Phizzl\Deployee\Descriptor\MarkdownDescriptorFormatter;
use Phizzl\Deployee\Di\DiContainer;
use Phizzl\Deployee\Di\DiContainerInterface;
use Phizzl\Deployee\Di\DiInjector;
use Phizzl\Deployee\Di\DiInjectorInterface;
use Phizzl\Deployee\Environment\Environment;
use Phizzl\Deployee\Filesystem\Finder;
use Phizzl\Deployee\Filesystem\PathHelper;
use Phizzl\Deployee\i18n;
use Phizzl\Deployee\Logger\Logger;
use Phizzl\Deployee\Plugins\PluginInterface;

if(!file_exists(__DIR__ . '/vendor/autoload.php')){
    echo "Please install composer first!" . PHP_EOL;
    exit(1);
}

if(version_compare(PHP_VERSION, "5.6.0", "<")){
    echo "You need at least PHP 5.6 to run Deployee" . PHP_EOL;
    exit(1);
}

$loader = require __DIR__ . '/vendor/autoload.php';

$di = new DiContainer();
$di->parameter('composer.class_loader', $loader);
$di->parameter('_basepath', __DIR__);

// config loader
$di->set('config.loader', function(){
    $finder = new Finder();
    $finder->depth(0)->files()->name('deployee.yml')->in([getcwd()]);
    if(!count($finder)){
        $finder = new Finder();
        $finder->depth(0)->files()->name('deployee.yml.dist')->in(__DIR__);
    }

    if(!count($finder)){
        throw new \Exception("No config file could be found!");
    }

    return new YamlConfigurationLoader($finder->first()->getPathname());
});

$di->set('config.merger', function(){
    return new ConfigMerger();
});

// config
$di->set('config', function(DiContainerInterface $di){
    $config = new Configuration();
    $config->setLoader($di->get('config.loader'));
    $config->initialize();
    return $config;
});

// di injector service
$di->set('di.injector', function(DiContainerInterface $di){
    $injector = new DiInjector();
    $injector->setContainer($di);
    return $injector;
});

// pathhelper
$di->set('filesystem.paths', function(DiContainerInterface $di){
    $pathHelper = new PathHelper();
    /* @var DiInjectorInterface $injector */
    $injector = $di->get('di.injector');
    $injector->injectDependencies($pathHelper);
    return $pathHelper;
});

// Logger service
$di->set('logger', function(DiContainerInterface $di){
    $logger = new Logger();
    $di->get('di.injector')->injectDependencies($logger);

    return $logger;
});

// dconnection
$di->set('db', function(DiContainerInterface $di){
    /* @var ConfigurationInterface $config */
    $config = $di->get('config');
    if(!is_array($config->get('db'))){
        throw new \Exception("Database configuration was not found. Did you run the d:install command?");
    }

    $db = new Db();
    $db->setConfiguration($config->get('db'));
    $db->connect();
    return $db;
});

$di->parameter('env.active', null);
$di->factory('env.loader', function(DiContainerInterface $di){
    if($di->get('env.active') === null){
        return null;
    }

    /* @var PathHelper $pathHelper */
    $pathHelper = $di->get('filesystem.paths');
    $finder = new Finder();
    $finder
        ->files()
        ->name($di->get('env.active') . '.yml')
        ->in([$pathHelper->getEnvironmentsPath()]);

    return count($finder) ? new YamlConfigurationLoader($finder->first()) : null;
});

$di->factory('env', function(DiContainerInterface $di){
    $env = new Environment();

    $config = $di->get('config');
    if($loader = $di->get('env.loader')){
        $envConfig = new Configuration();
        $envConfig->setLoader($loader);
        $envConfig->initialize();
        $config = $di->get('config.merger')->merge($config, $envConfig);
    }

    $env->setConfiguration($config);

    return $env;
});

$di->set('deployment.tasks.dispatcher', function(DiContainerInterface $di){
    $list = [];
    /* @var DiInjectorInterface $injector */
    $injector = $di->get('di.injector');
    foreach($di->get('config')->get('system')['tasks']['dispatcher'] as $dispatcherClass){
        $dispatcher = new $dispatcherClass;
        $injector->injectDependencies($dispatcher);
        $list[] = $dispatcher;
    }

    return $list;
});

$di->set('deployment.descriptor.formatter', function(){
    return new MarkdownDescriptorFormatter();
});

$di->set('deployment.tasks.descriptor', function(DiContainerInterface $di){
    $list = [];
    /* @var DiInjectorInterface $injector */
    $injector = $di->get('di.injector');
    foreach($di->get('config')->get('system')['tasks']['descriptors'] as $descriptorClass){
        $descriptor = new $descriptorClass;
        $injector->injectDependencies($descriptor);
        $list[] = $descriptor;
    }

    return $list;
});

$di->set('deployment.validator', function(DiContainerInterface $di){
    /* @var DiInjectorInterface $injector */
    $injector = $di->get('di.injector');
    $validator = new DeploymentDispatchValidator();
    $injector->injectDependencies($validator);

    return $validator;
});

$di->set('i18n', function(){
    return new i18n();
});

$di->set('plugins', function(DiContainerInterface $di){
    /* @var DiInjectorInterface $injector */
    $injector = $di->get('di.injector');
    $pluginContainer = new DiContainer();

    $plugins = $di->get('config')->get('system')['plugins'];
    foreach($plugins as $pluginClass){
        /* @var PluginInterface $plugin */
        $plugin = new $pluginClass;
        $injector->injectDependencies($plugin);
        $plugin->initialize();
        $pluginContainer->parameter($plugin->getId(), $plugin);
    }

    return $pluginContainer;
});

// default injector definitions
/* @var DiInjectorInterface $injector */
$injector = $di->get('di.injector');
$injector->addInjectionDefinition(
    'Phizzl\Deployee\Di\DiContainerInjectableInterface',
    'setContainer',
    $di
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\Logger\LoggerInjectableInterface',
    'setLogger',
    'logger'
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\Db\DbInjectableInterface',
    'setDb',
    'db'
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\Environment\EnvironmentInjectableInterface',
    'setEnvironment',
    'env'
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\Descriptor\DescriptorFormatterInjectableInterface',
    'setDescriptorFormatter',
    'deployment.descriptor.formatter'
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\i18nInjectableInterface',
    'setI18n',
    'i18n'
);

$injector->addInjectionDefinition(
    'Phizzl\Deployee\Plugins\PluginContainerInjectableImplementation',
    'setPluginCOntainer',
    'plugins'
);

// Initialize plugins
$di->get('plugins');

return $di;