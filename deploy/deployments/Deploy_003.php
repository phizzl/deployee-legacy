<?php


use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Add new table 'test'
 * @ticket NXS-002
 */
class Deploy_003 extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {
        $this->mysqlFile($this->asset('Deploy_003.sql'));

        return parent::define();
    }
}