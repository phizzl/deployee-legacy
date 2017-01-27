<?php


namespace Deployee\Deployments;


use Deployee\ExecutionStatusAwareInterface;

interface DeploymentInterface extends ExecutionStatusAwareInterface
{
    /**
     * Perform deployment
     */
    public function deploy();

    /**
     * Perform rollback
     */
    public function rollback();

    /**
     * @return string
     */
    public function getDeploymentId();
}