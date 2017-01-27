<?php


namespace Deployee\Deployments\Tasks;


use Deployee\ExecutionStatusAwareInterface;

interface TaskInterface extends ExecutionStatusAwareInterface
{
    /**
     * @return string
     */
    public function getTaskIdentifier();

    /**
     * Execute the task
     */
    public function execute();

    /**
     * Undo the task
     */
    public function undo();
}