<?php


namespace Deployee\Console\Commands;

use Deployee\Db\Adapter\Mysql\Table;
use Deployee\Db\DbManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure(){
        $this
            ->setName('deployee:init')
            ->setDescription('Run initialization')
            ->setHelp('Help');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        /* @var DbManager $dbm */
        $dbm = $this->container['db'];
        $this->createHistoryTable($dbm, $output);
        $this->createAuditTable($dbm, $output);

        $output->write("Initialization done!");
    }

    /**
     * @param DbManager $dbm
     * @param OutputInterface $output
     */
    private function createHistoryTable(DbManager $dbm, OutputInterface $output){
        /* @var Table $table */

        $table = $dbm->table('deployee_history');
        if($table->exists()){
            return;
        }

        $output->writeln("Creating table \"{$table->getName()}\"");
        $table
            ->addColumn('deployment_id', 'char', array('length' => 128))
            ->addColumn('context', 'text')
            ->addColumn('deploydate', 'datetime')
            ->addColumn('instance', 'char', array('length' => 128))
            ->addIndex(array('instance'))
            ->setPrimaryKey(array('deployment_id'))
            ->create();
    }

    /**
     * @param DbManager $dbm
     * @param OutputInterface $output
     */
    private function createAuditTable(DbManager $dbm, OutputInterface $output){
        /* @var Table $table */

        $table = $dbm->table('deployee_task_audit');
        if(!$table->exists()){
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'int', array('length' => 128, 'autoincrement' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('task_identifier', 'varchar', array('length' => 255))
                ->addColumn('context', 'text')
                ->addColumn('success', 'int', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->addIndex(array('instance'))
                ->addIndex(array('deployment_id'))
                ->setPrimaryKey(array('id'))
                ->create();
        }

        $table = $dbm->table('deployee_deployment_audit');
        if(!$table->exists()) {
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'int', array('length' => 128, 'autoincrement' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('context', 'text')
                ->addColumn('success', 'int', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->addIndex(array('instance'))
                ->addIndex(array('deployment_id'))
                ->setPrimaryKey(array('id'))
                ->create();
        }
    }
}