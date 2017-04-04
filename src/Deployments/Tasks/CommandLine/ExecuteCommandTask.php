<?php


namespace Deployee\Deployments\Tasks\CommandLine;



use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;
use Phizzl\PhpShellCommand\ExecTimeout;
use Phizzl\PhpShellCommand\ShellCommand;

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
    public function __construct($command, $timeout = null){
        $this->command = new ShellCommand($command, null, $timeout === null ? null : new ExecTimeout($timeout));
        $this->getContext()->set('command', $command);
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        if(!($return = $this->command->run(ShellCommand::OUTPUT_TYPE_BOTH))
            || $return['status'] !== ShellCommand::STATUS_OK){
            throw new \Exception("Error while executing command");
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