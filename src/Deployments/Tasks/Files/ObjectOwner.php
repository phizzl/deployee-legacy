<?php

namespace Deployee\Deployments\Tasks\Files;

use Deployee\Deployments\Tasks\TaskExecutionException;

class ObjectOwner
{
    /**
     * @var string
     */
    private $owner;
    /**
     * @var string
     */
    private $group;

    /**
     * @var bool
     */
    private $recursive;

    /**
     * ObjectOwner constructor.
     * @param string $owner
     * @param string|null $group
     */
    public function __construct($owner = null, $group = null, $recursive = false){
        $this->owner = $owner;
        $this->group = $group;
        $this->recursive = $recursive;
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     * @throws \Exception
     */
    public function applyTo($path){
        if($this->owner && !chown($path, $this->owner)){
            throw new TaskExecutionException("Unable to change owner to \"{$this->owner}\" for \"$path\"");
        }

        if($this->group && !chgrp($path, $this->group)){
            throw new TaskExecutionException("Unable to change group to \"{$this->group}\" for \"$path\"");
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