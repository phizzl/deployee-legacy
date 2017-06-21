<?php

namespace Phizzl\Deployee\Descriptor;

use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\i18nInjectableInterface;
use Phizzl\Deployee\Traits\DescriptorFormatterInjectableImplementation;
use Phizzl\Deployee\Traits\i18nInjectableImplementation;

abstract class AbstractTaskDescriptor implements TaskDescriptorInterface, i18nInjectableInterface
{
    use DescriptorFormatterInjectableImplementation;
    use i18nInjectableImplementation;

    /**
     * @var array
     */
    protected $describableTasks = [];

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDescribeTask(TaskDefinitionInterface $taskDefinition)
    {
        $return = false;
        foreach($this->describableTasks as $class){
            if($taskDefinition instanceof $class){
                $return = true;
                break;
            }
        }

        return $return;
    }


    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return mixed
     */
    abstract public function describe(TaskDefinitionInterface $taskDefinition);
}