<?php


namespace Deployee\Deployments;


use Deployee\ContainerAwareInterface;
use Deployee\ContextContainingInterface;
use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\Database\DatabaseManager;
use Deployee\Deployments\Tasks\TaskInterface;
use Deployee\DIContainer;

class DeploymentAudit implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param TaskInterface $task
     */
    public function addTaskToAudit(DeploymentInterface $deployment, TaskInterface $task){
        $now = new \DateTime();
        $auditTable = $this->getDatabaseManager()->table('deployee_task_audit');
        $auditTable->insert(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'task_identifier' => $task->getTaskIdentifier(),
            'context' => $task instanceof ContextContainingInterface ? json_encode($task->getContext()->getContents()) : '',
            'deploydate' => $now->format(\DateTime::ATOM),
            'success' => $task->getExecutionStatus(),
            'instance' => $this->container['config']->getEnvironment()->getInstanceId()
        ))->saveData();
    }

    /**
     * @param TaskInterface $task
     */
    public function addDeploymentToAudit(DeploymentInterface $deployment){
        $now = new \DateTime();
        $auditTable = $this->getDatabaseManager()->table('deployee_deployment_audit');
        $auditTable->insert(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'context' => $deployment instanceof ContextContainingInterface
                ? json_encode($deployment->getContext()->getContents())
                : '',
            'deploydate' => $now->format(\DateTime::ATOM),
            'success' => $deployment->getExecutionStatus(),
            'instance' => $this->container['config']->getEnvironment()->getInstanceId()
        ))->saveData();
    }

    /**
     * @return DatabaseManager
     */
    private function getDatabaseManager(){
        return $this->container['db'];
    }

    /**
     * @return MysqlAdapter
     */
    private function getMysqlAdapter(){
        return $this->getDatabaseManager()->getAdapter('mysql');
    }
}