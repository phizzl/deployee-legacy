<?php


namespace Deployee\Deployments;


interface DeploymentInterface
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