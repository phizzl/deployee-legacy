<?php


namespace Deployee\Console;


use Deployee\ContainerAwareInterface;
use Deployee\DIContainer;
use Symfony\Component\Console\Command\Command;

class Application extends \Symfony\Component\Console\Application implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param Command $command
     * @return null|Command
     */
    public function add(Command $command){
        if(!$command = parent::add($command)){
            return $command;
        }

        if($command instanceof ContainerAwareInterface){
            $command->setContainer($this->container);
        }

        return $command;
    }
}