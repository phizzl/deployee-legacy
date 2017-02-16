<?php


namespace Deployee\Core\Console\Commands;

use Deployee\Deployments\Manager;
use Deployee\Descriptions\DescribableInterface;
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
        $Manager = new Manager();
        $Manager->setContainer($this->container);
        $deployments = $Manager->getNextDeployments();

        foreach($deployments as $deployment){
            $output->writeln("Deploy class: ". get_class($deployment) . "\n");
            if($deployment instanceof DescribableInterface){
                $output->writeln($deployment->getDescription()->getDescription('DE'));
            }
        }
    }
}