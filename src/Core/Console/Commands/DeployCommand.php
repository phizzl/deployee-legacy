<?php


namespace Deployee\Core\Console\Commands;

use Deployee\Deployments\Manager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('deployee:deploy')
            ->setDescription('Run deployment')
            ->setHelp('Help');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        $Manager = new Manager();
        $Manager->setContainer($this->container);
        $Manager->setOutput($output);

        $Manager->runNextDeployments();

    }
}