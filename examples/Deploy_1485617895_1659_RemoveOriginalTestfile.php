<?php

class Deploy_1485617895_1659_RemoveOriginalTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->removeFile(__DIR__ . '/test.txt');

        /* @var DatabaseManager $dbm */
        $dbm = $this->container['db'];
        $this->changeTable(
            $dbm
                ->table('my_test_table')
                ->addColumn('specialIndex', 'char', array('length' => 32, 'collation' => 'latin1_general_ci'))
                ->addColumn('insertdate', 'date')
                ->addIndex(array('specialIndex'))
        );
    }
}