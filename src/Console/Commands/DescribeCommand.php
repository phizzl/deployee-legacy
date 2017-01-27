<?php


namespace Deployee\Console\Commands;

use Deployee\Database\Adapter\Mysql\Table;
use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\Database\DatabaseManager;
use Deployee\Deployments\DeploymentManager;
use Deployee\Deployments\DescribableInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DescribeCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('deployee:describe')
            ->setDescription('Describe deployment')
            ->setHelp('Help');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        $deploymentManager = new DeploymentManager();
        $deploymentManager->setContainer($this->container);
        $deployments = $deploymentManager->getNextDeployments();

        foreach($deployments as $deployment){
            $output->writeln("Deploy class: ". get_class($deployment) . "\n");
            if($deployment instanceof DescribableInterface){
                $output->writeln($deployment->getDescription()->getDescription('DE'));
            }
        }
    }
}