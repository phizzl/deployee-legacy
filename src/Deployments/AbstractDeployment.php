<?php


namespace Deployee\Deployments;


use Deployee\ContainerAwareInterface;
use Deployee\Core\Contexts\Context;
use Deployee\Core\Contexts\ContextContainingInterface;
use Deployee\Core\Database\Adapter\Mysql\Table;
use Deployee\Deployments\Tasks\Files\CreateFileTask;
use Deployee\Deployments\Tasks\Files\RemoveFileTask;
use Deployee\Deployments\Tasks\Files\SetFileGroupTask;
use Deployee\Deployments\Tasks\Files\SetFileOwnerTask;
use Deployee\Deployments\Tasks\Files\SetFilePermissionTask;
use Deployee\Deployments\Tasks\Files\UpdateFileTask;
use Deployee\Deployments\Tasks\Mysql\ChangeTableTask;
use Deployee\Deployments\Tasks\Mysql\CreateTableTask;
use Deployee\Deployments\Tasks\Mysql\ExecFileTask;
use Deployee\Deployments\Tasks\OxidEshop\ActivateModuleTask;
use Deployee\Deployments\Tasks\OxidEshop\DeactivateModuleTask;
use Deployee\Deployments\Tasks\OxidEshop\SetConfigTask;
use Deployee\Deployments\Tasks\TaskInterface;
use Deployee\Descriptions\DeploymentDescription;
use Deployee\Descriptions\DescribableInterface;
use Deployee\DIContainer;
use Deployee\Plugins\PluginInterface;

abstract class AbstractDeployment implements ContainerAwareInterface, DeploymentInterface, ContextContainingInterface, DescribableInterface
{
    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $tasks;

    /**
     * @var Context
     */
    protected $context;

    /**
     * AbstractDeployment constructor.
     */
    public function __construct(){
        $this->tasks = array();
        $this->context = new Context();

    }

     /**
      * @param DIContainer $container
      */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @return Context
     */
    public function getContext(){
        return $this->context;
    }

    /**
     * @return string
     */
    public function getDeploymentId(){
        return get_class($this);
    }

    /**
     * Configure Deployment
     */
    abstract function configure();

    /**
     * @inheritdoc
     */
    public function getTasks(){
        $this->configure();
        return $this->tasks;
    }

    /**
     * @inheritdoc
     */
    public function rollback(){

    }

    /**
     * @param string $name
     * @return PluginInterface
     */
    protected function plugin($name){
        if(!isset($this->container['plugins'][$name])){
            throw new \RuntimeException("Plugin \"$name\" is not registered");
        }

        /* @var PluginInterface $plugin */
        $plugin = $this->container['plugins'][$name];
        $plugin->setDeployment($this);

        return $plugin;
    }

    /**
     * @param TaskInterface $task
     * @return $this
     */
    public function addTask(TaskInterface $task){
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * @param string $target
     * @param string $content
     * @return AbstractDeployment
     */
    protected function createFile($target, $content){
        return $this->addTask(new CreateFileTask($target, $content));
    }

    /**
     * @param string $target
     * @param string $content
     * @return AbstractDeployment
     */
    protected function updateFile($target, $content){
        return $this->addTask(new UpdateFileTask($target, $content));
    }

    /**
     * @param string $target
     * @param string $permission
     * @param bool $recursive
     * @return AbstractDeployment
     */
    protected function setFilePermission($target, $permission, $recursive = false){
        return $this->addTask(new SetFilePermissionTask($target, $permission, $recursive));
    }

    /**
     * @param string $target
     * @param string $owner
     * @param bool $recursive
     * @return AbstractDeployment
     */
    protected function setFileOwner($target, $owner, $recursive = false){
        return $this->addTask(new SetFileOwnerTask($target, $owner, $recursive));
    }

    /**
     * @param string $target
     * @param string $group
     * @param bool $recursive
     * @return AbstractDeployment
     */
    protected function setFileGroup($target, $group, $recursive = false){
        return $this->addTask(new SetFileGroupTask($target, $group, $recursive));
    }

    /**
     * @param string $target
     * @return AbstractDeployment
     */
    protected function removeFile($target){
        return $this->addTask(new RemoveFileTask($target));
    }

    /**
     * @param Table $table
     * @return AbstractDeployment
     */
    protected function createTable(Table $table){
        return $this->addTask(new CreateTableTask($table));
    }

    /**
     * @param Table $table
     * @return AbstractDeployment
     */
    protected function changeTable(Table $table){
        return $this->addTask(new ChangeTableTask($table));
    }

    /**
     * @param $filepath
     * @return AbstractDeployment
     */
    protected function executeSqlFile($filepath){
        return $this->addTask(new ExecFileTask($filepath, $this->container['db']));
    }

    /**
     * @return DeploymentDescription
     */
    public function getDescription(){
        $this->configure();
        $description = new DeploymentDescription();
        $description->setDeployment($this);
        if($jiraUrl = $this->container['config']->get('jira')){
            $description->setJiraUrl($jiraUrl);
        }
        foreach($this->tasks as $task){
            if($task instanceof DescribableInterface){
                $description->addTaskDescription($task->getDescription());
            }
        }

        return $description;
    }
}