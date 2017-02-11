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
use Deployee\Deployments\Tasks\TaskExecutionException;
use Deployee\Deployments\Tasks\TaskInterface;
use Deployee\Descriptions\DeploymentDescription;
use Deployee\DIContainer;
use Deployee\ExecutionStatusAwareInterface;

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
     * @var
     */
    protected $audit;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var int
     */
    protected $status;

    /**
     * AbstractDeployment constructor.
     */
    public function __construct(DeploymentAudit $audit){
        $this->tasks = array();
        $this->context = new Context();
        $this->audit = $audit;

    }

     /**
      * @param DIContainer $container
      */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param int $status
     */
    public function setExecutionStatus($status){
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getExecutionStatus(){
        return $this->status === null ? ExecutionStatusAwareInterface::EXECUTION_NOT_EXECUTED : $this->status;
    }

    /**
     * @return Context
     */
    public function getContext(){
        return $this->context;
    }

    /**
     * Setup
     */
    public function setUp(){

    }

    /**
     * Tear down
     */
    public function tearDown(){

    }

    /**
     * @param TaskInterface $task
     */
    public function beforeTask(TaskInterface $task){

    }

    /**
     * @param TaskInterface $task
     */
    public function afterTask(TaskInterface $task){
        $this->audit->addTaskToAudit($this, $task);
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
    public function deploy(){
        $this->setUp();

        $this->configure();
        foreach($this->tasks as $task){
            $this->beforeTask($task);
            $this->executeOneTask($task);
            $this->afterTask($task);
        }

        $this->tearDown();
    }

    /**
     * @param TaskInterface $task
     * @throws TaskExecutionException
     */
    private function executeOneTask(TaskInterface $task){
        try{
            $task->execute();
            $task->setExecutionStatus(TaskInterface::EXECUTION_SUCCESS);
        }
        catch(\Exception $e){
            $task->setExecutionStatus(TaskInterface::EXECUTION_FAILED);
            if($task instanceof ContextContainingInterface){
                $task->getContext()->set('error', $e->getMessage());
            }
            $newEx = new TaskExecutionException($e->getMessage(), $e->getCode(), $e);
            $newEx->setContext(new Context(array('task' => $task)));
            $this->audit->addTaskToAudit($this, $task);
            throw $newEx;
        }
    }

    /**
     * @inheritdoc
     */
    public function rollback(){

    }

    /**
     * @param TaskInterface $task
     * @return $this
     */
    protected function addTask(TaskInterface $task){
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
     * @param string $moduleident
     * @return AbstractDeployment
     */
    protected function oxidActivateModule($moduleident){
        return $this->addTask(new ActivateModuleTask($moduleident));
    }

    /**
     * @param string $moduleident
     * @return AbstractDeployment
     */
    protected function oxidDeactivateModule($moduleident){
        return $this->addTask(new DeactivateModuleTask($moduleident));
    }

    /**
     * @param string $name
     * @param string $type
     * @param mixed $value
     * @param string|null $module
     * @return AbstractDeployment
     */
    protected function oxidSetConfig($name, $type, $value, $module = null){
        return $this->addTask(new SetConfigTask($type, $name, $value, $module));
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