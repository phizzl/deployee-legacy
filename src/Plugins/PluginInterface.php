<?php


namespace Deployee\Plugins;


use Deployee\ContainerAwareInterface;
use Deployee\Deployments\DeploymentInterface;

interface PluginInterface extends ContainerAwareInterface
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * @param DeploymentInterface $deployment
     */
    public function setDeployment(DeploymentInterface $deployment);
    
    public function initialize();
}
