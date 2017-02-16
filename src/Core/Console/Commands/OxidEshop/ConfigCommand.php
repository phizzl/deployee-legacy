<?php


namespace Deployee\Core\Console\Commands\OxidEshop;

use Deployee\Core\Configuration\Environment;
use Deployee\Core\Console\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('oxid:config')
            ->setDescription('Sets a configuration value')
            ->addOption('shopid', null, InputArgument::OPTIONAL)
            ->addArgument('valuefilepath', InputArgument::REQUIRED)
            ->setHelp('Help');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        /* @var Environment $env */
        $env = $this->container['config']->getEnvironment();
        if(!($oxidPath = $env->get('oxid_path'))
            || !is_file("$oxidPath/bootstrap.php")){
            throw new \Exception("No oxid_path is configured to the environment or the path is inaccessible!");
        }

        if($shopId = $input->getOption('shopid')){
            $_GET = array('shp' => $shopId);
        }

        require_once $oxidPath . '/bootstrap.php';

        $valuefilepath = $input->getArgument('valuefilepath');
        if(!is_file($valuefilepath)
            || !is_readable($valuefilepath)
            || !($config = unserialize(file_get_contents($valuefilepath)))){
            throw new \Exception("Could not read file with serialized config var array");
        }

        oxRegistry::getConfig()->saveShopConfVar(
            $config['type'],
            $config['name'],
            $config['value'],
            null,
            isset($config['module']) ? $config['module'] : null
        );
        $output->writeln("Wrote OXID eShop config \"{$config['name']} ({$config['type']})\"");
    }
}