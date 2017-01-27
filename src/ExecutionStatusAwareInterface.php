<?php


namespace Deployee;


interface ExecutionStatusAwareInterface
{
    const EXECUTION_FAILED = 0;

    const EXECUTION_NOT_EXECUTED = 1;

    const EXECUTION_SUCCESS = 2;

    /**
     * @param $status
     */
    public function setExecutionStatus($status);

    /**
     * @return int
     */
    public function getExecutionStatus();
}