<?php


namespace Deployee\Database;


use Deployee\ContainerAwareInterface;
use Deployee\Database\Adapter\AdapterInterface;
use Deployee\DIContainer;

class DatabaseManager implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @var array
     */
    private $adapter;

    public function __construct(){
        $this->adapter = array();
    }

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param string $type
     * @param AdapterInterface $adapter
     */
    public function registerAdapter($type, AdapterInterface $adapter){
        $this->adapter[$type] = $adapter;
    }

    /**
     * @param string $type
     * @return AdapterInterface
     * @throws \Exception
     */
    public function getAdapter($type){
        if(!isset($this->adapter[$type])){
            throw new \Exception("Unregistered adapter requested \"$type\"");
        }
        return $this->adapter[$type];
    }
}