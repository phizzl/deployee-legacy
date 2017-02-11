<?php


namespace Deployee\Core\Events;


use Symfony\Component\Console\Application;

class ApplicationCreateEvent extends AbstractEvent
{
    const NAME = "ApplicationCreateEvent";

    /**
     * @var Application
     */
    private $application;

    /**
     * @param Application $console
     */
    public function setConsole(Application $application){
        $this->application = $application;
    }

    /**
     * @return Application
     */
    public function getApplication(){
        return $this->application;
    }
}