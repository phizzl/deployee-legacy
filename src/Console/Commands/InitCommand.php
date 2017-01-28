<?php


namespace Deployee\Console\Commands;

use Deployee\Database\Adapter\Mysql\Table;
use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\Database\DatabaseManager;
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
        /* @var DatabaseManager $dbm */
        $dbm = $this->container['db'];

        $this->createHistoryTable($dbm, $output);
        $this->createAuditTable($dbm, $output);

        $output->write("Initialization done!");
    }

    /**
     * @param DatabaseManager $dbm
     * @param OutputInterface $output
     */
    private function createHistoryTable(DatabaseManager $dbm, OutputInterface $output){
        /* @var Table $table */

        $table = $dbm->table('deployee_history', array(
            'primary_key' => 'deployment_id',
            'indices' => array(
                'idx_deployment_id' => 'deployment_id',
                'idx_instance' => 'instance'
            )
        ));
        if($table->exists()){
            return;
        }

        $output->writeln("Creating table \"{$table->getName()}\"");
        $table
            ->addColumn('deployment_id', 'char', array('length' => 128))
            ->addColumn('context', 'text')
            ->addColumn('deploydate', 'datetime')
            ->addColumn('instance', 'char', array('length' => 128))
            ->addIndex('instance')
            ->create();
    }

    /**
     * @param DatabaseManager $dbm
     * @param OutputInterface $output
     */
    private function createAuditTable(DatabaseManager $dbm, OutputInterface $output){
        /* @var Table $table */

        $table = $dbm->table('deployee_task_audit', array(
            'primary_key' => 'id',
            'indices' => array(
                'idx_deployment_id' => 'deployment_id',
                'idx_instance' => 'instance'
            )
        ));
        if(!$table->exists()){
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'integer', array('length' => 128, 'autoincrement' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('task_identifier', 'string', array('length' => 255))
                ->addColumn('context', 'text')
                ->addColumn('success', 'integer', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->addIndex('instance')
                ->addIndex('deployment_id')
                ->create();
        }

        $table = $dbm->table('deployee_deployment_audit', array(
            'primary_key' => 'id'
        ));

        if(!$table->exists()) {
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'integer', array('length' => 128, 'autoincrement' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('context', 'text')
                ->addColumn('success', 'integer', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->addIndex('instance')
                ->addIndex('deployment_id')
                ->create();
        }
    }
}