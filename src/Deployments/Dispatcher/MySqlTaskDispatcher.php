<?php


namespace Phizzl\Deployee\Deployments\Dispatcher;


use Phizzl\Deployee\Db\DbInjectableInterface;
use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Traits\DbInjectableImplementation;

class MySqlTaskDispatcher implements TaskDispatcherInterface, DbInjectableInterface
{
    use DbInjectableImplementation;

    /**
     * @var array
     */
    private $dispatchableTaskClasses = [
        'Phizzl\Deployee\Deployments\Tasks\MySqlFileTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\MySqlQueryTaskDefinition',
    ];

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchDefinition(TaskDefinitionInterface $taskDefinition)
    {
        $return = false;
        foreach($this->dispatchableTaskClasses as $class){
            if($taskDefinition instanceof $class){
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition)
    {
        foreach($this->dispatchableTaskClasses as $class){
            if($taskDefinition instanceof  $class){
                call_user_func_array(
                    [$this, lcfirst(str_replace('TaskDefinition', '', basename($class)))],
                    [$taskDefinition]
                );
            }
        }
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    private function mySqlFile(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        $this->db()->execute(file_get_contents($params['file']));
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    private function mySqlQuery(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        $this->db()->execute($params['query']);
    }
}