<?php

namespace Phizzl\Deployee\Deployments;


use Phizzl\Deployee\Deployments\Tasks\CallTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\CommentTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\DirectoryCreateTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\DirectoryModifyTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\DirectoryRemoveTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\FileCreateTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\FileModifyTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\FileRemoveTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\MySqlFileTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\MySqlQueryTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Environment\EnvironmentInjectableInterface;
use Phizzl\Deployee\Filesystem\PathHelper;
use Phizzl\Deployee\Logger\LoggerInjectableInterface;
use Phizzl\Deployee\Plugins\PluginInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use Phizzl\Deployee\Traits\EnvironmentInjectableImplementation;
use Phizzl\Deployee\Traits\LoggerInjectableImplementation;

abstract class AbstractDeploymentDefinition implements
    DeploymentDefinitionInterface,
    EnvironmentInjectableInterface,
    DiContainerInjectableInterface,
    LoggerInjectableInterface,
    TaskDefinitionAddableInterface
{
    use EnvironmentInjectableImplementation;
    use DiContainerInjectableImplementation;
    use LoggerInjectableImplementation;

    /**
     * @var array
     */
    private $taskDefinitions;

    /**
     * AbstractDeploymentDefinition constructor.
     */
    public function __construct()
    {
        $this->taskDefinitions = [];
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function addTaskDefinition(TaskDefinitionInterface $taskDefinition)
    {
        $this->taskDefinitions[] = $taskDefinition;
    }

    /**
     * @return array
     */
    public function define()
    {
        return $this->taskDefinitions;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    protected function call(callable $callback)
    {
        $this->addTaskDefinition(new CallTaskDefinition($callback));
        return $this;
    }

    /**
     * @param string $query
     * @return $this
     */
    protected function mysqlQuery($query)
    {
        $this->addTaskDefinition(new MySqlQueryTaskDefinition($query));
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    protected function mysqlFile($file)
    {
        $this->addTaskDefinition(new MySqlFileTaskDefinition($file));
        return $this;
    }

    /**
     * @param $dir
     * @param string $permissions
     * @return $this
     */
    protected function dirCreate($dir, $owner = '', $group = '', $permissions = '')
    {
        $this->addTaskDefinition(new DirectoryCreateTaskDefinition($dir, $owner, $group, $permissions));
        return $this;
    }

    /**
     * @param string $dir
     * @param bool $recursive
     * @return $this
     */
    protected function dirRemove($dir, $recursive = false)
    {
        $this->addTaskDefinition(new DirectoryRemoveTaskDefinition($dir, $recursive));
        return $this;
    }

    /**
     * @param string $dir
     * @param bool $recursive
     * @param string $owner
     * @param string $group
     * @param string $permissions
     * @return $this
     */
    protected function dirModify($dir, $recursive = false, $owner = '', $group = '', $permissions = '')
    {
        $this->addTaskDefinition(new DirectoryModifyTaskDefinition($dir, $recursive, $owner, $group, $permissions));
        return $this;
    }

    /**
     * @param string $file
     * @param string $contents
     * @param string $owner
     * @param string $group
     * @param string $permissions
     * @return $this
     */
    protected function fileCreate($file, $contents = '', $owner = '', $group = '', $permissions = '')
    {
        $this->addTaskDefinition(new FileCreateTaskDefinition($file, $contents, $owner, $group, $permissions));
        return $this;
    }

    /**
     * @param string $file
     * @param string $contents
     * @param string $owner
     * @param string $group
     * @param string $permissions
     * @return $this
     */
    protected function fileModify($file, $contents = '', $owner = '', $group = '', $permissions = '')
    {
        $this->addTaskDefinition(new FileModifyTaskDefinition($file, $contents, $owner, $group, $permissions));
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    protected function fileRemove($file)
    {
        $this->addTaskDefinition(new FileRemoveTaskDefinition($file));
        return $this;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function asset($filename)
    {
        /* @var PathHelper $pathHelper */
        $pathHelper = $this->container()->get('filesystem.paths');
        return $pathHelper->getAssetsPath() . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function path($filename)
    {
        /* @var PathHelper $pathHelper */
        $pathHelper = $this->container()->get('filesystem.paths');
        return $pathHelper->getBasePath() . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param string $message
     * @return $this
     */
    protected function addComment($message)
    {
        $this->addTaskDefinition(new CommentTaskDefinition($message));
        return $this;
    }

    /**
     * @param string $name
     * @return PluginInterface
     */
    protected function plugin($name)
    {
        return $this->container()->get('plugins')->get($name);
    }
}