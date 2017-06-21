<?php

namespace Phizzl\Deployee\Deployments;


interface DeploymentDefinitionInterface
{
    /**
     * @return array
     */
    public function define();
}