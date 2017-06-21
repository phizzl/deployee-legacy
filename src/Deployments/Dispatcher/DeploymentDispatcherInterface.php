<?php


namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;

interface DeploymentDispatcherInterface
{
    /**
     * @param DeploymentDefinitionInterface $deploymentDefinition
     * @return void
     */
    public function dispatch(DeploymentDefinitionInterface $deploymentDefinition);
}