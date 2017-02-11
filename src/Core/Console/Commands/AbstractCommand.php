<?php


namespace Deployee\Core\Console\Commands;



use Deployee\ContainerAwareInterface;
use Deployee\DIContainer;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }
}