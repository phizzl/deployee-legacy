<?php

namespace Deployee\Deployments\Tasks\Files;

use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Deployments\Tasks\TaskExecutionException;

class AbstractFileTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $contents;

    /**
     * AbstractFileMigration constructor.
     * @param string $target
     * @param string $contents
     * @param ObjectPermission|null $permission
     * @param ObjectOwner|null $owner
     */
    public function __construct($target, $contents){
        $this->target = $target;
        $this->contents = $contents;
        $this->getContext()->set('file', $target);
    }

    /**
     * @throws \Exception
     */
    public function execute(){
        if(!file_put_contents($this->target, $this->contents)){
            throw new TaskExecutionException("Could not write file \"{$this->target}\"");
        }
    }

     /**
      * @inheritdoc
      */
     public function undo(){

     }


 }