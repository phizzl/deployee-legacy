<?php


namespace Deployee\Deployments;


use Deployee\Deployments\Tasks\TaskInterface;

interface DeploymentInterface
{
    /**
     * Perform deployment
     */
    public function getTasks();

    /**
     * @param TaskInterface $task
     */
    public function addTask(TaskInterface $task);

    /**
     * @return string
     */
    public function getDeploymentId();
}