<?php


namespace Deployee\Deployments\Tasks\Files;

use Deployee\Deployments\Tasks\TaskExecutionException;
use Deployee\Descriptions\TaskDescription;

class UpdateFileTask extends AbstractFileTask
{
    /**
     * @inheritdoc
     */
    public function execute(){
        if(!file_exists($this->target)){
            throw new TaskExecutionException("File does not exist \"{$this->target}\"");
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
            "Die existierende Datei \"{$this->target}\" wird mit folgendem Inhalt geschrieben: " .
            "\"" . substr($this->contents, 0, 100) . "\""
        );

        return $desc;
    }
}