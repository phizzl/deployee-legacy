<?php

use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Your description goes here
 * @ticket DEPLOYEE-001
 */
class Deploy_1498069388_594ab98ca12ab extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {
        $this->plugin('test');
        return parent::define();
    }
}