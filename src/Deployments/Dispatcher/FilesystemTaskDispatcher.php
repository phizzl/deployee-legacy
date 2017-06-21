<?php


namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Deployments\TaskException;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Filesystem\Finder;

class FilesystemTaskDispatcher implements TaskDispatcherInterface
{
    /**
     * @var array
     */
    private $dispatchableTaskClasses = [
        'Phizzl\Deployee\Deployments\Tasks\FileCreateTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\FileModifyTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\FileRemoveTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryCreateTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryModifyTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryRemoveTaskDefinition',
    ];

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchDefinition(TaskDefinitionInterface $taskDefinition)
    {
        $return = false;
        foreach($this->dispatchableTaskClasses as $class){
            if($taskDefinition instanceof $class){
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition)
    {
        foreach($this->dispatchableTaskClasses as $class){
            if($taskDefinition instanceof  $class){
                call_user_func_array(
                    [$this, lcfirst(str_replace('TaskDefinition', '', basename($class)))],
                    [$taskDefinition]
                );
            }
        }
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function directoryCreate(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(is_dir($params['file'])){
            throw new TaskException("Directory already exists");
        }

        if(!mkdir($params['file'])){
            throw new TaskException("Directory could not be created");
        }

        $this->handleFileOperations($params);
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function directoryModify(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(!is_dir($params['file'])){
            throw new TaskException("Directory does not exist");
        }

        $this->handleFileOperations($params);
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function directoryRemove(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(!is_dir($params['file'])){
            throw new TaskException("Directory does not exist");
        }

        if(!rmdir($params['file'])){
            throw new TaskException("Directory could not be removed");
        }
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function fileCreate(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(is_file($params['file'])){
            throw new TaskException("File already exists");
        }

        if(file_put_contents($params['file'], isset($params['contents']) ? $params['contents'] : '') === false){
            throw new TaskException("File could not be created");
        }

        $this->handleFileOperations($params);
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function fileModify(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(!is_file($params['file'])){
            throw new TaskException("File does not exists");
        }

        if(file_put_contents($params['file'], isset($params['contents']) ? $params['contents'] : '') === false){
            throw new TaskException("File could not be modified");
        }

        $this->handleFileOperations($params);
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @throws TaskException
     */
    private function fileRemove(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        if(!is_file($params['file'])){
            throw new TaskException("File does not exists");
        }

        if(!unlink($params['file'])){
            throw new TaskException("File could not be created");
        }
    }

    /**
     * @param array $params
     * @throws TaskException
     */
    private function handleFileOperations(array $params)
    {
        $files = [];
        if(isset($params['recursive'])
            && $params['recursive']){
            $finder = new Finder();
            $finder->in([$params['file']]);
            /* @var \SplFileInfo $file */
            foreach($finder as $file){
                $files[] = $file->getPathname();
            }
        }
        else{
            $files[] = $params['file'];
        }

        foreach($files as $file){
            if(isset($params['permissions'])
                && $params['permissions'] !== ''
                && !chmod($file, (double)$params['permissions'])){
                throw new TaskException("Permissions could not be set");
            }

            if(isset($params['owner'])
                && $params['owner'] !== ''
                && !chown($file, $params['owner'])){
                throw new TaskException("Owner could not be set");
            }

            if(isset($params['group'])
                && $params['group'] !== ''
                && !chgrp($file, $params['group'])){
                throw new TaskException("Group could not be set");
            }
        }
    }
}