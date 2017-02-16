<?php


namespace Deployee\Deployments\Tasks\Mysql;

use Deployee\Core\Database\DbManager;
use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Deployments\Tasks\TaskExecutionException;
use Deployee\Descriptions\TaskDescription;

class ExecFileTask extends AbstractTask
{
    /**
     * @var string
     */
    private $filepath;

    /**
     * @var DbManager
     */
    private $dbm;

    /**
     * ExecFileTask constructor.
     * @param $filepath
     * @param DbManager $dbm
     */
    public function __construct($filepath, DbManager $dbm){
        $this->filepath = $filepath;
        $this->dbm = $dbm;
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        if(!is_file($this->filepath)
            || !is_readable($this->filepath)){
            throw new TaskExecutionException("The SQL file \"{$this->filepath}\" can not be read!");
        }

        $this->dbm->execute(file_get_contents($this->filepath));
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
            "Führe SQL-Datei \"{$this->filepath}\" aus"

        );

        return $desc;
    }
}