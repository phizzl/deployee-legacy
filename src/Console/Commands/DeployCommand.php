<?php


namespace Deployee\Console\Commands;

use Deployee\Deployments\DeploymentInterface;
use Deployee\Deployments\DeploymentManager;
use Deployee\ExecutionStatusAwareInterface;
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
        $deploymentManager = new DeploymentManager();
        $deploymentManager->setContainer($this->container);
        $deploymentManager->setOutput($output);

        $deploymentManager->runNextDeployments();

    }
}