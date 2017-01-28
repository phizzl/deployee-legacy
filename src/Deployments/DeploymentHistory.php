<?php

namespace Deployee\Deployments;


use Deployee\ContainerAwareInterface;
use Deployee\ContextContainingInterface;
use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\Database\DatabaseManager;
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
        $stm = $this->getMysqlAdapter()->prepare($sql);
        $stm->bindValue(':id', $deployment->getDeploymentId());
        $stm->execute();

        return (bool)$stm->fetchColumn();
    }

    /**
     * @param DeploymentInterface $deployment
     */
    public function addToHistory(DeploymentInterface $deployment){
        $now = new \DateTime();
        $historyTable = $this->getDatabaseManager()->table('deployee_history');
        $historyTable->insert(array(
            'deployment_id' => $deployment->getDeploymentId(),
            'deploydate' => $now->format(\DateTime::ATOM),
            'context' => $deployment instanceof ContextContainingInterface
                ? json_encode($deployment->getContext()->getContents())
                : '',
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