<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Traits\DescriptorFormatterInjectableImplementation;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use phpDocumentor\Reflection\DocBlockFactory;

class DeploymentDescriptor implements DeploymentDescriptorInterface, DiContainerInjectableInterface
{
    use DescriptorFormatterInjectableImplementation;
    use DiContainerInjectableImplementation;

    /**
     * @var DocBlockFactory
     */
    private $docblockFactory;

    /**
     * DeploymentDescriptor constructor.
     */
    public function __construct()
    {
        $this->docblockFactory = DocBlockFactory::createInstance();
    }

    public function describe(DeploymentDefinitionInterface $deploymentDefinition)
    {
        $formatter = $this->formatter();
        $return = $formatter->headline(get_class($deploymentDefinition));

        $docBlock = $this->docblockFactory->create(new \ReflectionClass($deploymentDefinition));
        if(count($docBlock->getTagsByName('ticket'))){
            $return .= $formatter->bold("Ticket:") .
                $formatter->write(" ") . implode(', ', $docBlock->getTagsByName('ticket'));
        }

        if(count($docBlock->getTagsByName('describe'))){
            $return .= $formatter->newline();
            /* @var Generic $tag */
            foreach($docBlock->getTagsByName('describe') as $tag){
                $return .= $formatter->quote((string)$tag);
            }
            $return .= $formatter->newline();
        }

        foreach($deploymentDefinition->define() as $task){
            $descriptor = $this->getTaskDescriptor($task);
            $return .= $descriptor->describe($task);
        }

        $return .= $formatter->line();
        $return .= $formatter->writeln("Generated " . date('d.m.Y, H:i:s'));

        return $return;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return TaskDescriptorInterface
     * @throws \Exception
     */
    private function getTaskDescriptor(TaskDefinitionInterface $taskDefinition)
    {
        /* @var TaskDescriptorInterface $descriptor */
        foreach($this->container()->get('deployment.tasks.descriptor') as $descriptor) {
            if($descriptor->canDescribeTask($taskDefinition)){
                return $descriptor;
            }
        }

        throw new \Exception("No task descriptor found");
    }
}