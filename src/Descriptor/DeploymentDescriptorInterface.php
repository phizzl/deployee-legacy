<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;

interface DeploymentDescriptorInterface extends DescriptorFormatterInjectableInterface
{
    /**
     * @param DeploymentDefinitionInterface $deploymentDefinition
     * @return string
     */
    public function describe(DeploymentDefinitionInterface $deploymentDefinition);
}