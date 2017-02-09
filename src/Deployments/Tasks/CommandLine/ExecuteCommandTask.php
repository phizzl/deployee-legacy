<?php


namespace Deployee\Deployments\Tasks\CommandLine;



use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class ExecuteCommandTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $command;

    /**
     * ExecuteCommandTask constructor.
     * @param string $command
     */
    public function __construct($command){
        $this->command = $command;
        $this->getContext()->set('command', $command);
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        if(false === system($this->command, $return)){
            throw new \Exception("Error while executing command: $this->command");
        }

        $this->context->set('return', $return);
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
            "Der Befehl \"{$this->command}\" wird ausgeführt.\n"
        );

        return $desc;
    }
}