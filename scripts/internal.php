<?php
/**
 *
 * @author Phillip Schleicher <schleicher@nexus-netsoft.com>
 */

use Deployee\Console\Application;
use Deployee\Console\Commands\OxidEshop\ModuleCommand;

$container = require __DIR__ . '/../bootstrap.php';
/* @var Application $app */
$app = $container['console'];

$app->addCommands(array(
    new ModuleCommand()
));

$app->run();