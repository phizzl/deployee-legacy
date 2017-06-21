<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;

interface TaskDescriptorInterface extends DescriptorFormatterInjectableInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDescribeTask(TaskDefinitionInterface $taskDefinition);

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return string
     */
    public function describe(TaskDefinitionInterface $taskDefinition);
}