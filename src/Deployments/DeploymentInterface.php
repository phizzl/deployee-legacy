<?php


namespace Deployee\Deployments;


use Deployee\ExecutionStatusAwareInterface;

interface DeploymentInterface extends ExecutionStatusAwareInterface
{
    /**
     * Perform deployment
     */
    public function getTasks();

    /**
     * @return string
     */
    public function getDeploymentId();
}