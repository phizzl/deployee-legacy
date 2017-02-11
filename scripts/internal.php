<?php
/**
 *
 * @author Phillip Schleicher <schleicher@nexus-netsoft.com>
 */

use Deployee\Core\Console\Application;
use Deployee\Core\Console\Commands\OxidEshop\ModuleCommand;
use Deployee\Core\Console\Commands\OxidEshop\ConfigCommand;

$container = require __DIR__ . '/../bootstrap.php';
/* @var Application $app */
$app = $container['console'];

$app->addCommands(array(
    new ModuleCommand(),
    new ConfigCommand()
));

$app->run();