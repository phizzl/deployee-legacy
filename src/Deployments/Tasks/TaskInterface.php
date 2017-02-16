<?php


namespace Deployee\Deployments\Tasks;


interface TaskInterface
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