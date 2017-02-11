<?php


namespace Deployee\Core\Events;

use Deployee\DIContainer;
use Symfony\Component\EventDispatcher\Event;

class AbstractEvent extends Event
{
    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * AbstractEvent constructor.
     * @param DIContainer $container
     */
    public function __construct(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @return DIContainer
     */
    public function getContainer(){
        return $this->container;
    }
}