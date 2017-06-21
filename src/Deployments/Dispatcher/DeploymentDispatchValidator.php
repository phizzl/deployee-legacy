<?php

namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Db\DbInjectableInterface;
use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;
use Phizzl\Deployee\Traits\DbInjectableImplementation;

class DeploymentDispatchValidator implements DeploymentDispatchValidatorInterface, DbInjectableInterface
{
    use DbInjectableImplementation;

    public function canBeDispatched(DeploymentDefinitionInterface $deploymentDefinition)
    {
        return !(bool)$this->db()->getOne("SELECT COUNT(*) FROM deployee_deploys WHERE name=?", [get_class($deploymentDefinition)]);
    }

    public function closeDeployment(DeploymentDefinitionInterface $deploymentDefinition)
    {
        $this->db()->execute("INSERT INTO deployee_deploys (name) VALUES (?)", [get_class($deploymentDefinition)]);
    }
}