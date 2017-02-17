<?php

use Deployee\Core\Database\DbManager;

class Deploy_1485505576_3809_RemoveTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');

        $this->removeFile(__DIR__ . '/test2.txt');

        /* @var DbManager $dbm */
        $dbm = $this->container['db'];
        $this->changeTable(
            $dbm
                ->table('my_test_table')
                ->changeColumn('oneNiceColumn', 'int', array('length' => 11))
        );
    }
}