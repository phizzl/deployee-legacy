<?php


namespace Deployee\Core\Console\Commands;

use Deployee\Skel\DeploymentSkel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('deployee:create')
            ->setDescription('Create deployment')
            ->setHelp('Help')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the deployment')
            ->addArgument('ticket', InputArgument::OPTIONAL, 'A ticket number');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        $name = $input->getArgument('name');
        $ticket = $input->getArgument('ticket');

        $skel = new DeploymentSkel();
        $skel->setContainer($this->container);
        if($filePath = $skel->create($name, $ticket)){
            $output->writeln("Deployment created \"$filePath\"");
        }
        else{
            $output->write("Unable to create Deployment");
        }
    }
}