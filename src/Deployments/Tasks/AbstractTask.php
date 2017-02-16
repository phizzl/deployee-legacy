<?php

namespace Deployee\Deployments\Tasks;


use Deployee\Core\Contexts\Context;
use Deployee\Core\Contexts\ContextContainingInterface;
use Deployee\Deployments\DescribableInterface;
use Deployee\Descriptions\TaskDescription;

abstract class AbstractTask implements TaskInterface, ContextContainingInterface, DescribableInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var TaskDescription
     */
    protected $description;

    /**
     * @inheritdoc
     */
    public function getTaskIdentifier(){
        return get_class($this);
    }

    /**
     * @inheritdoc
     */
    abstract public function execute();

    /**
     * @inheritdoc
     */
    abstract public function undo();

    /**
     * @return Context
     */
    public function getContext(){
        if($this->context === null){
            $this->context = new Context();
        }

        return $this->context;
    }

    /**
     * @return TaskDescription
     */
    public function getDescription(){
        if($this->description === null){
            $this->description = new TaskDescription();
        }

        return $this->description;
    }
}