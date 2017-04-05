<?php

use Deployee\Core\Configuration\Configuration;
use Deployee\Core\Console\Application;
use Deployee\Core\Database\Adapter\MysqlAdapter;
use Deployee\Core\Database\DbManager;
use Deployee\Core\DependencyResolver;
use Deployee\DIContainer;
use Deployee\Core\Configuration\Environment;
use Deployee\Plugins\PluginInterface;
use Pimple\Container;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

define('DEPLOYEE_BASEDIR', __DIR__);

$autoloadFiles = array(
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../../autoload.php'
);

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        $loader = require_once $autoloadFile;
        break;
    }
}

$container = new DIContainer();
$container['loader'] = $loader;
$container['config'] = function(){
    $directories = array(getcwd(), getcwd() . DIRECTORY_SEPARATOR . 'config');
    $configfile = null;
    foreach($directories as $dir){
        if(file_exists($dir . DIRECTORY_SEPARATOR . "deployee.yml")){
            $configfile = $dir . DIRECTORY_SEPARATOR . "deployee.yml";
            break;
        }
    }

    if(!$configfile
        || !is_readable($configfile)
        || !($parameter = Yaml::parse(file_get_contents($configfile)))){
        throw new Exception("Could not find configuration!");
    }

    return new Configuration($parameter);
};

// Dependency resolver
$container['dependencyresolver'] = function($c){
    return new DependencyResolver($c);
};

$container['console'] = function($c){
    $console = new Application('Deployee', '0.0.1');
    $c['dependencyresolver']->resolve($console);

    return $console;
};

/* @var Configuration $config */
$config = $container['config'];
$defaultEnv = $config->get('default_environment');
if(php_sapi_name() == 'cli'){
    $bootArguments = array($_SERVER['argv'][0]);
    foreach($_SERVER['argv'] as $i => $value){
        if(substr($value, 0,5) == '--env'){
            $bootArguments[2] = $value;
            unset($_SERVER['argv'][$i]);
            $_SERVER['argc']--;
        }
    }

    $inputOption =  new InputOption('env', 'e', InputOption::VALUE_REQUIRED, 'The deployee environment', $defaultEnv);
    $inputDefinition = new InputDefinition(array($inputOption));
    $input = new ArgvInput($bootArguments,$inputDefinition);
    define('ENVIRONMENT', $input->getOption('env'));
}
else {
    die("Cannot use an non cli");
}

$envs = $config->get('environments');
if(!isset($envs[ENVIRONMENT])){
    throw new \Exception("Environment undefined \"".ENVIRONMENT."\"");
}

$config->setEnvironment(new Environment($envs[ENVIRONMENT]));

// Database manager
$container['db'] = function($c){
    $adapter = new MysqlAdapter();
    $adapter->setContainer($c);
    $db = new DbManager();
    $db->setAdapter($adapter);

    return $db;
};

// Load plugins
$pluginContainer = new Container();
$plugins = $config->get('plugins', array());
foreach($plugins as $name => $pluginClass){
    /* @var PluginInterface $plugin */
    $plugin = new $pluginClass;
    $container['dependencyresolver']->resolve($plugin);
    $plugin->init();
    $pluginContainer[$name] = $plugin;
    $plugin->initialize();
}

$container['plugins'] = $pluginContainer;

return $container;
