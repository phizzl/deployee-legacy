<?php


namespace Deployee\Deployments\Tasks\CommandLine;



use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class ExecuteInternalCommandTask extends ExecuteCommandTask
{
    /**
     * ExecuteInternalCommandTask constructor.
     * @param string$command
     */
    public function __construct($command){
        parent::__construct("php " . BASEDIR . "/scripts/internal.php " . $command);
    }
}