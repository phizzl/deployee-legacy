<?php


namespace Deployee\Deployments\Tasks\CommandLine;


class ExecuteInternalCommandTask extends ExecuteCommandTask
{
    /**
     * ExecuteInternalCommandTask constructor.
     * @param string $command
     */
    public function __construct($command){
        parent::__construct("php " . DEPLOYEE_BASEDIR . "/scripts/deployee " . $command);
    }
}