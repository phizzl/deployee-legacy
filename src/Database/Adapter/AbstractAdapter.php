<?php


namespace Deployee\Database\Adapter;


use Deployee\ContainerAwareInterface;
use Deployee\DIContainer;

abstract class AbstractAdapter implements ContainerAwareInterface, AdapterInterface
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

    /**
     * @inheritdoc
     */
    abstract public function setConfiguration(array $conf);
}