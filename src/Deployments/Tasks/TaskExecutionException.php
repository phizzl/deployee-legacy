<?php

namespace Deployee\Deployments\Tasks;


use Deployee\Core\Contexts\Context;

class TaskExecutionException extends \Exception
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function setContext(Context $context){
        $this->context = $context;
    }

    /**
     * @return Context
     */
    public function getContext(){
        return $this->context;
    }
}