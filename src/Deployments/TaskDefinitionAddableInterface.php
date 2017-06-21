<?php


namespace Phizzl\Deployee\Deployments;


use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;

interface TaskDefinitionAddableInterface
{
    public function addTaskDefinition(TaskDefinitionInterface $taskDefinition);
}