<?php


namespace Phizzl\Deployee\Descriptor;


use Phizzl\Deployee\Deployments\Tasks\TaskDefinitionInterface;
use Phizzl\Deployee\Filesystem\Finder;

class FilesystemTaskDescriptor extends AbstractTaskDescriptor
{
    protected $describableTasks = [
        'Phizzl\Deployee\Deployments\Tasks\FileCreateTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\FileModifyTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\FileRemoveTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryCreateTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryModifyTaskDefinition',
        'Phizzl\Deployee\Deployments\Tasks\DirectoryRemoveTaskDefinition',
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

    private function fileCreate(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Erstelle Datei"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function fileModify(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Bearbeite Datei"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function fileRemove(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Entferne Datei"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function directoryCreate(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Erstelle Verzeichnis"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function directoryModify(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Bearbeite Verzeichnis"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function directoryRemove(TaskDefinitionInterface $taskDefinition)
    {
        $return = $this->formatter()->writeln($this->i18n()->get("Entferne Verzeichnis"));
        $return .= $this->describeFileOperations($taskDefinition->define());

        return $return;
    }

    private function describeFileOperations(array $params) {
        $return = "";
        $i18n = $this->i18n();
        $formatter = $this->formatter();
        $files = [];
        if(isset($params['recursive'])
            && $params['recursive']){
            $finder = new Finder();
            $finder->in([$params['file']]);
            /* @var \SplFileInfo $file */
            foreach($finder as $file){
                $files[] = $file->getPathname();
            }
        }
        else{
            $files[] = $params['file'];
        }

        foreach($files as $file){
            $return .= $formatter->writeln($i18n->get("Pfad") . ": $file");

            if(isset($params['contents'])
                && $params['contents'] !== ''){
                $return .= $formatter->write("\t--> " . $i18n->get("Inhalt") . ": ");
                $return .= $formatter->writeln(wordwrap($params['contents'], 100, '...', true));
            }

            if(isset($params['permissions'])
                && $params['permissions'] !== ''){
                $return .= $formatter->write("\t--> " . $i18n->get("Dateirechte") . ": ");
                $return .= $formatter->writeln($params['permissions']);
            }

            if(isset($params['owner'])
                && $params['owner'] !== ''){
                $return .= $formatter->write("\t--> " . $i18n->get("Besitzer") . ": ");
                $return .= $formatter->writeln($params['owner']);
            }

            if(isset($params['group'])
                && $params['group'] !== ''){
                $return .= $formatter->write("\t--> " . $i18n->get("Gruppe") . ": ");
                $return .= $formatter->writeln($params['group']);
            }
        }

        $return .= $formatter->newline();

        return $return;
    }
}