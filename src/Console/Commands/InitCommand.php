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
        /* @var MysqlAdapter $mysqlAdapter */
        $mysqlAdapter = $dbm->getAdapter('mysql');

        $this->createHistoryTable($mysqlAdapter, $output);
        $this->createAuditTable($mysqlAdapter, $output);


        $output->write("Initialization done!");
    }

    /**
     * @param MysqlAdapter $mysqlAdapter
     * @param OutputInterface $output
     */
    private function createHistoryTable(MysqlAdapter $mysqlAdapter, OutputInterface $output){
        /* @var Table $table */

        $table = $mysqlAdapter->table('deployee_history', array(
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
            ->create();
    }

    /**
     * @param MysqlAdapter $mysqlAdapter
     * @param OutputInterface $output
     */
    private function createAuditTable(MysqlAdapter $mysqlAdapter, OutputInterface $output){
        /* @var Table $table */

        $table = $mysqlAdapter->table('deployee_task_audit', array(
            'primary_key' => 'id',
            'indices' => array(
                'idx_deployment_id' => 'deployment_id',
                'idx_instance' => 'instance'
            )
        ));
        if(!$table->exists()){
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'int', array('length' => 128, 'auto_increment' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('task_identifier', 'varchar', array('length' => 255))
                ->addColumn('context', 'text')
                ->addColumn('success', 'tinyint', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->create();
        }



        $table = $mysqlAdapter->table('deployee_deployment_audit', array(
            'primary_key' => 'id',
            'indices' => array(
                'idx_deployment_id' => 'deployment_id',
                'idx_instance' => 'instance'
            )
        ));

        if(!$table->exists()) {
            $output->writeln("Creating table \"{$table->getName()}\"");
            $table
                ->addColumn('id', 'int', array('length' => 128, 'auto_increment' => true))
                ->addColumn('deployment_id', 'char', array('length' => 128))
                ->addColumn('context', 'text')
                ->addColumn('success', 'tinyint', array('length' => 1, 'signed' => false, 'default' => 0))
                ->addColumn('deploydate', 'datetime')
                ->addColumn('instance', 'char', array('length' => 128))
                ->create();
        }
    }
}