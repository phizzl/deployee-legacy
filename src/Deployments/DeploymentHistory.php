<?php

namespace Deployee\Deployments;


use Deployee\ContainerAwareInterface;
use Deployee\ContextContainingInterface;
use Deployee\Db\DbManager;
use Deployee\DIContainer;

class DeploymentHistory implements ContainerAwareInterface
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
     * @param DeploymentInterface $deployment
     * @return bool
     */
    public function isDeployed(DeploymentInterface $deployment){
        $sql = "SELECT COUNT(deployment_id) FROM deployee_history WHERE deployment_id=:id";
        $dbm = $this->getDatabaseManager();

        return (bool)$dbm->getOne($sql, array(':id' => $deployment->getDeploymentId()));
    }

    /**
     * @param DeploymentInterface $deployment
     */
    public function addToHistory(DeploymentInterface $deployment){
        $now = new \DateTime();
        $historyTable = $this->getDatabaseManager()->table('deployee_history');
        $historyTable->addInsertData(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'deploydate' => $now->format(\DateTime::ATOM),
            'context' => $deployment instanceof ContextContainingInterface
                ? json_encode($deployment->getContext()->getContents())
                : '',
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