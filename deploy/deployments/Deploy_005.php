<?php


use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Remove table 'test'
 * @ticket NXS-004
 */
class Deploy_005 extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {
        $this->mysqlQuery(<<<EOL
DROP TABLE `test`;
EOL
);

        return parent::define();
    }
}