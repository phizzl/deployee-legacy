<?php


namespace Deployee\Deployments;


use Deployee\ContainerAwareInterface;
use Deployee\Core\Contexts\ContextContainingInterface;
use Deployee\Core\Database\DbManager;
use Deployee\Deployments\Tasks\TaskInterface;
use Deployee\DIContainer;

class Audit implements ContainerAwareInterface
{
    const STATUS_FAILED = 0;

    const STATUS_OK = 1;

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
     * @param DeploymentInterface $deployment
     * @param TaskInterface $task
     * @param int $status
     */
    public function addTaskToAudit(DeploymentInterface $deployment, TaskInterface $task, $status){
        $now = new \DateTime();
        $auditTable = $this->getDatabaseManager()->table('deployee_task_audit');
        $auditTable->addInsertData(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'task_identifier' => $task->getTaskIdentifier(),
            'context' => $task instanceof ContextContainingInterface ? json_encode($task->getContext()->getContents()) : '',
            'deploydate' => $now->format(\DateTime::ATOM),
            'success' => (int)$status,
            'instance' => $this->container['config']->getEnvironment()->getInstanceId()
        ))->saveData();
    }

    /**
     * @param DeploymentInterface $deployment
     * @param int $status
     */
    public function addDeploymentToAudit(DeploymentInterface $deployment, $status){
        $now = new \DateTime();
        $auditTable = $this->getDatabaseManager()->table('deployee_deployment_audit');
        $auditTable->addInsertData(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'context' => $deployment instanceof ContextContainingInterface
                ? json_encode($deployment->getContext()->getContents())
                : '',
            'deploydate' => $now->format(\DateTime::ATOM),
            'success' => (int)$status,
            'instance' => $this->container['config']->getEnvironment()->getInstanceId()
        ))->saveData();
    }

    /**
     * @return DbManager
     */
    private function getDatabaseManager(){
        return $this->container['db'];
    }
}