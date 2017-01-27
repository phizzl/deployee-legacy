<?php


namespace Deployee\Deployments;


use Composer\Autoload\ClassLoader;
use Deployee\Configuration;
use Deployee\ContainerAwareInterface;
use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\DIContainer;

class DeploymentManager implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @var DeploymentHistory
     */
    private $history;

    /**
     * @var DeploymentAudit
     */
    private $audit;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
        $this->history = new DeploymentHistory();
        $this->history->setContainer($this->container);
        $this->audit = new DeploymentAudit();
        $this->audit->setContainer($this->container);
    }

    /**
     * @return DeploymentHistory
     */
    public function getHistory(){
        return $this->history;
    }

    /**
     * @return DeploymentAudit
     */
    public function getAudit(){
        return $this->audit;
    }


    public function getNextDeployments(){
        /* @var Configuration $config */
        $config = $this->container['config'];
        $env = $config->getEnvironment();
        $deploymentPath = $env->getDeploymentPath();
        $deployments = $this->getAllDeployments($deploymentPath);

        $nextDeployments = array();
        /* @var AbstractDeployment $deployment */
        foreach($deployments as $deployment){
            if(!$this->getHistory()->isDeployed($deployment)){
                $nextDeployments[] = $deployment;
            }
        }

        return $nextDeployments;
    }

    /**
     * @param string $deploymentPath
     * @return array
     */
    protected function getAllDeployments($deploymentPath){
        $deployments = array();
        /* @var ClassLoader $autoloader */
        $autoloader = $this->container['loader'];

        foreach(new \DirectoryIterator($deploymentPath) as $file){
            if($file->isDir()
                || $file->isDot()
                || !$file->isReadable()
                || $file->getExtension() != 'php'
                || substr($file->getBasename(), 0, 6) != 'Deploy'){
                continue;
            }


            $className = substr($file->getBasename(), 0, (strlen($file->getBasename())-(strlen($file->getExtension())+1)));
            $autoloader->addClassMap(array(
                $className => $file->getRealPath()
            ));

            $fileParts = explode('_', $className);
            unset($fileParts[0]);
            /* @var AbstractDeployment $deployment */
            $deployment = new $className($this->getAudit());
            if($deployment instanceof ContainerAwareInterface){
                $deployment->setContainer($this->container);
            }
            $deployments[implode('_', $fileParts)] = $deployment;
        }

        ksort($deployments);

        return array_values($deployments);
    }
}