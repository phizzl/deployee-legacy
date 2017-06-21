<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;

class MySqlTaskDescriptor extends AbstractTaskDescriptor
{
    protected $describableTasks = [
        'Phizzl\Deployee\Deployments\Tasks\MySqlFileTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\MySqlQueryTaskDefinition',
    ];

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    public function describe(TaskDefinitionInterface $taskDefinition)
    {
        foreach($this->describableTasks as $class){
            if($taskDefinition instanceof  $class){
                return call_user_func_array(
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
        $formatter = $this->formatter();
        $i18n = $this->i18n();

        return $formatter->writeln(
            $formatter->bold(
                $i18n->get("Fuehre MySQL Datei aus")
            )
        ) . $formatter->writeln("\t{$params['file']}");
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     */
    private function mySqlQuery(TaskDefinitionInterface $taskDefinition)
    {
        $params = $taskDefinition->define();
        $formatter = $this->formatter();
        $i18n = $this->i18n();

        $return = $formatter->writeln(
            $formatter->bold(
                $i18n->get("Fuehre MySQL Query aus")
            )
        );

        $return .= $formatter->writeln('```mysql');
        $return .= $formatter->writeln(wordwrap($params['query'], 500, "...", true));
        $return .= $formatter->writeln('```');

        return $return;
    }
}