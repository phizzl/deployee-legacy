<?php


namespace Deployee\Plugins;


use Deployee\Deployments\DeploymentInterface;
use Deployee\DIContainer;

class AbstractPlugin implements PluginInterface
{
    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * @var DeploymentInterface
     */
    protected $deployment;

    /**
     * @inheritdoc
     */
    public function init(){

    }


    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param DeploymentInterface $deployment
     */
    public function setDeployment(DeploymentInterface $deployment){
        $this->deployment = $deployment;
    }
    
    public function initialize(){
    
    }
}
