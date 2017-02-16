<?php

namespace Deployee\Deployments\Tasks\Files;

use Deployee\Deployments\Tasks\TaskExecutionException;

class ObjectPermission
{
    /**
     * @var int
     */
    private $permission;

    /**
     * @var bool
     */
    private $recursive;

    /**
     * ObjectPermission constructor.
     * @param int $permission
     * @param bool $recursive
     */
    public function __construct($permission, $recursive = false){
        $this->permission = $permission;
        $this->recursive = $recursive;
    }

    /**
     * @param string $path
     * @return bool
     * @throws \Exception
     */
    public function applyTo($path){
        if(!chmod($path, $this->permission)){
            throw new TaskExecutionException("Unable to set permissions to \"$path\"");
        }

        if(!$this->recursive
            || !is_dir($path)){
            return true;
        }

        foreach(new \DirectoryIterator($path) as $item){
            if(!$item->isDot()){
                $this->applyTo($item->getRealPath());
            }
        }

        return true;
    }
}