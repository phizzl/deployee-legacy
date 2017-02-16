<?php


namespace Deployee\Core;


use Deployee\ContainerAwareInterface;
use Deployee\DIContainer;

class DependencyResolver
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * DependecyResolver constructor.
     * @param DIContainer $conatiner
     */
    public function __construct(DIContainer $conatiner){
        $this->container = $conatiner;
    }

    public function resolve($object){
        if(!is_object($object)){
            throw new \Exception("Only objects can be resolved!");
        }

        if($object instanceof ContainerAwareInterface){
            $object->setContainer($this->container);
        }

        return $object;
    }
}