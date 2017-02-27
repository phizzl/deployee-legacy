<?php


namespace Deployee\Plugins;


use Deployee\ContainerAwareInterface;
use Deployee\Deployments\DeploymentInterface;

interface PluginInterface extends ContainerAwareInterface
{
    /**
     * @param DeploymentInterface $deployment
     */
    public function setDeployment(DeploymentInterface $deployment);
}