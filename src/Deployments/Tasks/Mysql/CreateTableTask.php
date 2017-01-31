<?php


namespace Deployee\Deployments\Tasks\Mysql;


use Deployee\Db\Adapter\Mysql\Table;
use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class CreateTableTask extends AbstractTask
{
    /**
     * @var Table
     */
    private $table;

    /**
     * CreateDatabaseTask constructor.
     * @param Table $table
     */
    public function __construct(Table $table){
        $this->table = $table;
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        $this->table->create();
    }

    /**
     * @inheritdoc
     */
    public function undo(){

    }

    /**
     * @return TaskDescription
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Erzeugen der Tabelle \"{$this->table->getName()}\" mit dem Statement \n\n" . $this->table->getCreateSql()

        );

        return $desc;
    }
}