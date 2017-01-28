<?php

namespace Deployee\Subscriber;


use Deployee\Database\Adapter\MysqlAdapter;
use Deployee\DIContainer;
use Deployee\Environment;
use Deployee\Events\DatabaseCreateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DatabaseCreateEventSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents(){
        return [
            DatabaseCreateEvent::NAME => 'onDatabaseCreate'
        ];
    }

    /**
     * @param DatabaseCreateEvent $event
     */
    public function onDatabaseCreate(DatabaseCreateEvent $event){
        $db = $event->getDatabaseManager();
        $container = $event->getContainer();
        /* @var Environment $env */
        $env = $event->getContainer()['config']->getEnvironment();

        $this->registerAdapterMysql($container, $env);

        foreach($env->getDatabaseConfiguration() as $type => $conf){
            $adapter = $container['db.adapter.'.$type];
            $db->registerAdapter($type, $adapter);
        }
    }

    /**
     * @param DIContainer $container
     */
    protected function registerAdapterMysql(DIContainer $container){
        $container['db.adapter.mysql'] = $container->factory(function($c){
            $env = $c['config']->getEnvironment();
            $adapter = new MysqlAdapter($env->getDatabaseConfiguration('mysql'));

            return $adapter;
        });
    }
}