<?php


use Deployee\Core\Console\Application;
use Deployee\Core\Console\Commands\OxidEshop\ModuleCommand;
use Deployee\Core\Console\Commands\OxidEshop\ConfigCommand;

define('INTERNAL', true);
$container = require __DIR__ . '/../bootstrap.php';
/* @var Application $app */
$app = $container['console'];

$app->addCommands(array(
    new ModuleCommand()
));

$app->run();