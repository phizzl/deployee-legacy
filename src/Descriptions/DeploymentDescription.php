<?php


namespace Deployee\Descriptions;


use Deployee\Deployments\AbstractDeployment;

class DeploymentDescription extends AbstractDescription
{
    /**
     * @var array
     */
    private $tasks;

    /**
     * @var AbstractDeployment
     */
    private $deployment;

    /**
     * @var string
     */
    private $jiraUrl;

    /**
     * DeploymentDescription constructor.
     */
    public function __construct(){
        $this->tasks = array();
    }

    /**
     * @param AbstractDeployment $deployment
     */
    public function setDeployment(AbstractDeployment $deployment){
        $this->deployment = $deployment;
    }

    /**
     * @param $jiraUrl
     */
    public function setJiraUrl($jiraUrl){
        $this->jiraUrl = $jiraUrl;
    }

    /**
     * @param TaskDescription $desc
     * @return $this
     */
    public function addTaskDescription(TaskDescription $desc){
        $this->tasks[] = $desc;
        return $this;
    }

    /**
     * @param string $lang
     * @return string
     */
    public function getDescription($lang){
        $markdown = new Markdown();
        if($ticket = $this->deployment->getContext()->get('ticket')){
            if($this->jiraUrl){
                $markdown
                    ->link($this->jiraUrl . "/browse/{$ticket}", "Ticket \"{$ticket}\"")
                    ->newLine()
                    ->writeln("=============");
            }
            else{
                $markdown->headline("Ticket \"{$ticket}\"");
            }
        }

        if($author = $this->deployment->getContext()->get('author')){
            $markdown->writeln("Author \"{$author}\"");
        }

        /* @var TaskDescription $task */
        foreach($this->tasks as $task){
            $markdown->dotList($task->getDescription($lang));
        }

        $markdown->lineSeparator();
        return $markdown->getContent();
    }
}