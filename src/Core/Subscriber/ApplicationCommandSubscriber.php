<?php


namespace Deployee\Core\Subscriber;

use Deployee\Core\Console\Commands\CreateCommand;
use Deployee\Core\Console\Commands\DeployCommand;
use Deployee\Core\Console\Commands\DescribeCommand;
use Deployee\Core\Console\Commands\InitCommand;
use Deployee\Core\Console\Commands\MigrateCommand;
use Deployee\Core\Events\ApplicationCreateEvent;
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