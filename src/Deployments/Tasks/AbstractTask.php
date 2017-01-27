<?php

namespace Deployee\Deployments\Tasks;


use Deployee\Context;
use Deployee\ContextContainingInterface;
use Deployee\Deployments\DescribableInterface;
use Deployee\Descriptions\TaskDescription;

abstract class AbstractTask implements TaskInterface, ContextContainingInterface, DescribableInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var int
     */
    protected $status;

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
     * @param int $status
     */
    public function setExecutionStatus($status){
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getExecutionStatus(){
        return $this->status === null ? TaskInterface::EXECUTION_NOT_EXECUTED : $this->status;
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