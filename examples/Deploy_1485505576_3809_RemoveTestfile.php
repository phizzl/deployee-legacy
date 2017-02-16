<?php

use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\Database\DatabaseManager;

class Deploy_1485505576_3809_RemoveTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');

        $this->removeFile(__DIR__ . '/test2.txt');

        /* @var DatabaseManager $dbm */
        $dbm = $this->container['db'];
        $this->changeTable(
            $dbm
                ->table('my_test_table')
                ->changeColumn('oneNiceColumn', 'int', array('length' => 11))
        );
    }
}