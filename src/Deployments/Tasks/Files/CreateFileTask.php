<?php


namespace Deployee\Deployments\Tasks\Files;



use Deployee\Deployments\Tasks\TaskExecutionException;
use Deployee\Descriptions\TaskDescription;

class CreateFileTask extends AbstractFileTask
{
    /**
     * @inheritdoc
     */
    public function execute(){
        if(file_exists($this->target)){
            throw new TaskExecutionException("File already exist \"{$this->target}\"");
        }

        parent::execute();
    }

    /**
     * @return TaskDescription
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Es wird die Datei \"{$this->target}\" erzeugt.\n" .
            "Es wird folgender Inhalt geschrieben \"" . substr($this->contents, 0, 100) . "\""
        );

        return $desc;
    }
}