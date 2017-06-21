<?php

use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Your description goes here
 * @ticket DEPLOYEE-001
 */
class Deploy_1496860125_593845dd0e4fd extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {
        $this->addComment("hallowelt");
        return parent::define();
    }
}