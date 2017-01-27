<?php


namespace Deployee\Deployments\Tasks\Files;



use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class RemoveFileTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $target;

    /**
     * AbstractFileMigration constructor.
     * @param string $target
     */
    public function __construct($target){
        $this->target = $target;
        $this->getContext()->set('file', $target);
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        if(!file_exists($this->target)){
            throw new \Exception("The file does not exist \"{$this->target}\"");
        }

        if(!unlink($this->target)){
            throw new \Exception("Could nor remove file \"{$this->target}\"");
        }
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
            "Die Datei \"{$this->target}\" wird entfernt.\n"
        );

        return $desc;
    }
}