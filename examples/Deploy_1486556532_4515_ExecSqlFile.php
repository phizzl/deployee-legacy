<?php

class Deploy_1486556532_4515_ExecSqlFile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->executeSqlFile(__DIR__ . '/Deploy_1486556532_4515_ExecSqlFile/awesome_file.sql');
    }
}