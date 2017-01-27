<?php


namespace Deployee\Subscriber;

use Deployee\Console\Commands\CreateCommand;
use Deployee\Console\Commands\DeployCommand;
use Deployee\Console\Commands\DescribeCommand;
use Deployee\Console\Commands\InitCommand;
use Deployee\Console\Commands\MigrateCommand;
use Deployee\Events\ApplicationCreateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationCommandSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents(){
        return [
            ApplicationCreateEvent::NAME => 'onConsoleCreate'
        ];
    }

    /**
     * @param ApplicationCreateEvent $event
     */
    public function onConsoleCreate(ApplicationCreateEvent $event){
        $event->getApplication()->add(new InitCommand());
        $event->getApplication()->add(new DeployCommand());
        $event->getApplication()->add(new DescribeCommand());
        $event->getApplication()->add(new CreateCommand());
    }
}