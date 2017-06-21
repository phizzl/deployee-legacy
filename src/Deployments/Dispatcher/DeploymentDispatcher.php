<?php


namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Logger\LoggerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use Phizzl\Deployee\Traits\LoggerInjectableImplementation;

class DeploymentDispatcher implements DeploymentDispatcherInterface, DiContainerInjectableInterface, LoggerInjectableInterface
{
    use DiContainerInjectableImplementation;
    use LoggerInjectableImplementation;

    /**
     * @param DeploymentDefinitionInterface $deploymentDefinition
     */
    public function dispatch(DeploymentDefinitionInterface $deploymentDefinition)
    {
        $this->logger()->info("Start dispatching deployment", [
            "deployment" => get_class($deploymentDefinition)
        ]);

        $tasks = $deploymentDefinition->define();
        /* @var TaskDefinitionInterface $task */
        foreach($tasks as $task){
            $dispatcher = $this->getTaskDispatcher($task);

            $this->logger()->info("Start dispatching task", [
                "deployment" => get_class($deploymentDefinition),
                "task" => get_class($task),
                "dispatcher" => get_class($dispatcher)
            ]);

            $dispatcher->dispatch($task);
        }
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return TaskDispatcherInterface
     * @throws \Exception
     */
    private function getTaskDispatcher(TaskDefinitionInterface $taskDefinition)
    {
        /* @var TaskDispatcherInterface $dispatcher */
        foreach($this->container()->get('deployment.tasks.dispatcher') as $dispatcher) {
            if($dispatcher->canDispatchDefinition($taskDefinition)){
                return $dispatcher;
            }
        }

        throw new \Exception("No task disptacher found");
    }
}