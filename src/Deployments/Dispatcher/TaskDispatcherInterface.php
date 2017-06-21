<?php

namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;

interface TaskDispatcherInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchDefinition(TaskDefinitionInterface $taskDefinition);

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return void
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition);
}