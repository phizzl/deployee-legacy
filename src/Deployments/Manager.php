<?php


namespace Deployee\Deployments;


use Composer\Autoload\ClassLoader;
use Deployee\Core\Configuration\Configuration;
use Deployee\ContainerAwareInterface;
use Deployee\Core\Contexts\ContextContainingInterface;
use Deployee\Deployments\Tasks\TaskInterface;
use Deployee\DIContainer;
use Deployee\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Manager implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @var History
     */
    private $history;

    /**
     * @var Audit
     */
    private $audit;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
        $this->history = new History();
        $this->audit = new Audit();
        $this->output = new NullOutput();

        $this->container['dependencyresolver']->resolve($this->history);
        $this->container['dependencyresolver']->resolve($this->audit);
    }

    /**
     * @param OutputInterface $output
     * @return $this
     */
    public function setOutput(OutputInterface $output){
        $this->output = $output;
        return $this;
    }

    /**
     * @return History
     */
    public function getHistory(){
        return $this->history;
    }

    /**
     * @return Audit
     */
    public function getAudit(){
        return $this->audit;
    }

    /**
     * @return array
     */
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
            /* @var DeploymentInterface $deployment */
            $deployment = new $className();
            $this->container['dependencyresolver']->resolve($deployment);
            $deployments[implode('_', $fileParts)] = $deployment;
        }

        ksort($deployments);

        return array_values($deployments);
    }

    /**
     * Runs all undeployed deployments
     */
    public function runNextDeployments(){
        if(!count($deployments = $this->getNextDeployments())){
            $this->output->writeln("Nothing to deploy :-)");
            return true;
        }

        /* @var DeploymentInterface $deployment */
        foreach($deployments as $deployment){
            if(!$this->runTasks($deployment)){
                $this->output->writeln("Deployment failed!");
                return false;
            }

            $this->getHistory()->addToHistory($deployment);
        }

        $this->output->writeln("Deployment succeeded!");
        return true;
    }

    /**
     * @param DeploymentInterface $deployment
     * @return bool
     */
    private function runTasks(DeploymentInterface $deployment){
        $this->output->writeln("Deploying \"{$deployment->getDeploymentId()}\"");

        /* @var TaskInterface $task */
        foreach($deployment->getTasks() as $task){
            try {
                $this->output->writeln("\tExecuting task \"{$task->getTaskIdentifier()}\"");
                $task->execute();
                $this->getAudit()->addTaskToAudit($deployment, $task, Audit::STATUS_OK);
            }
            catch(\Exception $e){
                foreach(array($task, $deployment) as $obj){
                    if($obj instanceof ContextContainingInterface){
                        $obj->getContext()->set('error', array(
                            "message" => $e->getMessage(),
                            "file" => $e->getFile(),
                            "line" => $e->getLine(),
                            "code" => $e->getCode()
                        ));
                    }
                }

                $this->output->writeln("\tTask \"{$task->getTaskIdentifier()}\" failed!");
                $this->output->writeln($e->getMessage(), OutputInterface::VERBOSITY_VERBOSE);
                $this->output->writeln($e->getTraceAsString(), OutputInterface::VERBOSITY_DEBUG);

                $this->getAudit()->addTaskToAudit($deployment, $task, Audit::STATUS_FAILED);
                $this->getAudit()->addDeploymentToAudit($deployment, Audit::STATUS_FAILED);
                return false;
            }
        }

        $this->output->writeln("Finished deploying \"{$deployment->getDeploymentId()}\"");
        $this->getAudit()->addDeploymentToAudit($deployment, Audit::STATUS_FAILED);

        return true;
    }
}