<?php


use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Add column 'name2' to table 'test'
 * @ticket NXS-003
 */
class Deploy_004 extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {
        $this->mysqlQuery(<<<EOL
ALTER TABLE `test`
	ADD COLUMN `name2` VARCHAR(50) NOT NULL DEFAULT 'test1' AFTER `name`;
EOL
);

        return parent::define();
    }
}