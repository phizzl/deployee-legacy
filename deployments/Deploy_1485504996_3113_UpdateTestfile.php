<?php

use Deployee\Database\DatabaseManager;

class Deploy_1485504996_3113_UpdateTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');
        $this->updateFile(__DIR__ . '/test.txt', 'This is my updated content!');
        $this->createFile(__DIR__ . '/test2.txt', 'Another test file');

        /* @var DatabaseManager $dbm */
        $dbm = $this->container['db'];
        $this->createTable(
            $dbm
                ->table('my_test_table')
                ->addColumn('id', 'int', array('length' => 128, 'autoincrement' => true))
                ->addColumn('oneNiceColumn', 'text')
                ->setPrimaryKey(array('id'))
        );
    }
}