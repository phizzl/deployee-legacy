<?php


namespace Phizzl\Deployee\Deployments\Dispatcher;

use Phizzl\Deployee\Deployments\Tasks\CommentTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;

class CommentTaskDispatcher implements TaskDispatcherInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchDefinition(TaskDefinitionInterface $taskDefinition)
    {
        return $taskDefinition instanceof CommentTaskDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition)
    {
        echo $taskDefinition->define()['comment'] . PHP_EOL;
    }
}