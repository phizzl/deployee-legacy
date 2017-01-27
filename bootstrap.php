<?php

use Deployee\Configuration;
use Deployee\Console\Application;
use Deployee\Database\DatabaseManager;
use Deployee\DIContainer;
use Deployee\Environment;
use Deployee\Events\ApplicationCreateEvent;
use Deployee\Events\DatabaseCreateEvent;
use Deployee\Subscriber\ApplicationCommandSubscriber;
use Deployee\Subscriber\DatabaseCreateEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

define('BASEDIR', __DIR__);

$loader = require __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container['loader'] = $loader;
$container['config'] = function($c){
    $configfile = __DIR__.'/config.yml';
    if(!is_readable($configfile)
        || !($parameter = Yaml::parse(file_get_contents($configfile)))){
        throw new Exception("Could not find configuration!");
    }

    return new Configuration($parameter);
};

$container['eventdispatcher'] = function($c){
    return new Symfony\Component\EventDispatcher\EventDispatcher();
};

$container['console'] = function($c){
    $console = new Application('Deployee', '0.0.1');
    $console->setContainer($c);

    $event = new ApplicationCreateEvent($c);
    $event->setConsole($console);
    $c['eventdispatcher']->dispatch(ApplicationCreateEvent::NAME, $event);

    return $console;
};

/* @var EventDispatcher $eventDispatcher */
$eventDispatcher = $container['eventdispatcher'];
$eventDispatcher->addSubscriber(new ApplicationCommandSubscriber());
$eventDispatcher->addSubscriber(new DatabaseCreateEventSubscriber());

$defaultEnv = $container['config']->get('default_environment');
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
    $inputDefinition = new InputDefinition([$inputOption]);
    $input = new ArgvInput($bootArguments,$inputDefinition);
    define('ENVIRONMENT', $input->getOption('env'));
}
else {
    die("Cannot use an non cli");
}

$envs = $container['config']->get('environments');
if(!isset($envs[ENVIRONMENT])){
    throw new \Exception("Environment undefined \"".ENVIRONMENT."\"");
}

$container['config']->setEnvironment(new Environment($envs[ENVIRONMENT]));

$container['db'] = function($c){
    $db = new DatabaseManager();
    $db->setContainer($c);
    $event = new DatabaseCreateEvent($c);
    $event->setDatabaseManager($db);
    $c['eventdispatcher']->dispatch(DatabaseCreateEvent::NAME, $event);

    return $db;
};

$container['db'];

return $container;