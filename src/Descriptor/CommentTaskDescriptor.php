<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\Tasks\CommentTaskDefinition;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Traits\DescriptorFormatterInjectableImplementation;

class CommentTaskDescriptor implements TaskDescriptorInterface
{
    use DescriptorFormatterInjectableImplementation;

    public function canDescribeTask(TaskDefinitionInterface $taskDefinition)
    {
        return $taskDefinition instanceof CommentTaskDefinition;
    }


    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function describe(TaskDefinitionInterface $taskDefinition)
    {
        return $this->formatter()->writeln($taskDefinition->define()['comment']);
    }
}