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
        $deployments = $deploymentManager->getNextDeployments();
        try{
            if(!count($deployments)){
                $output->writeln("Nothing to deploy :-)");
                return;
            }

            /* @var DeploymentInterface $deployment */
            foreach($deployments as $deployment){
                $output->writeln("Deploying \"{$deployment->getDeploymentId()}\"");
                $deployment->deploy();
                $deploymentManager->getHistory()->addToHistory($deployment);
                $deploymentManager->getAudit()->addDeploymentToAudit($deployment);
                $output->writeln("Finished deploying \"{$deployment->getDeploymentId()}\"");
            }

            $output->writeln("Deployment done");
        }
        catch (\Exception $e){
            $deployment->setExecutionStatus(ExecutionStatusAwareInterface::EXECUTION_FAILED);
            $deployment->getContext()->set('error', $e->getMessage());
            $deploymentManager->getAudit()->addDeploymentToAudit($deployment);

            $output->writeln("Error while executing deployment \"{$deployment->getDeploymentId()}\"");
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
        }
    }
}