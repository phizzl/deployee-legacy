<?php

namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;

interface DeploymentDispatchValidatorInterface
{
    /**
     * @param DeploymentDefinitionInterface $deploymentDefinition
     * @return bool
     */
    public function canBeDispatched(DeploymentDefinitionInterface $deploymentDefinition);

    /**
     * @param DeploymentDefinitionInterface $deploymentDefinition
     * @return void
     */
    public function closeDeployment(DeploymentDefinitionInterface $deploymentDefinition);
}