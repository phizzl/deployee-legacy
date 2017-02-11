<?php


namespace Deployee\Core\Console\Commands\OxidEshop;

use Deployee\Core\Configuration\Environment;
use Deployee\Core\Console\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('oxid:module')
            ->setDescription('(De)Activates a module')
            ->addOption('shopid', null, InputArgument::OPTIONAL)
            ->addArgument('moduleident', InputArgument::REQUIRED)
            ->addArgument('action', InputArgument::REQUIRED)
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

        $ident = $input->getArgument('moduleident');
        $module = oxNew('oxmodule');
        if(!$module->load($ident)){
            throw new \Exception("Module with ident \"$ident\" cannot be loaded");
        }

        if($input->getArgument('action') == 'deactivate'){
            $action = 'deactivate';
        }
        else{
            $action = 'activate';
        }

        if(class_exists('oxModuleInstaller')){
            $installer = oxNew('oxModuleInstaller');
            call_user_func_array(array($installer, $action), array($module));
        }
        else{
            $module->$action();
        }

        $output->writeln("Executed \"$action\" on module \"{$module->getTitle()} ({$ident})\"");
    }
}