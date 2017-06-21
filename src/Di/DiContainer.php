<?php

namespace Phizzl\Deployee\Di;


use Pimple\Container;

class DiContainer implements DiContainerInterface
{

    /**
     * @var Container
     */
    private $container;

    /**
     * DiContainer constructor.
     */
    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * @param string $name
     * @param callable $callable
     */
    public function set($name, $callable)
    {
        $di = $this;
        $this->container[$name] = function() use ($di, $callable) { return $callable($di); };
    }

    /**
     * @param string $name
     * @param callable $callable
     */
    public function factory($name, $callable)
    {
        $di = $this;
        $this->container[$name] = $this->container->factory(function() use ($di, $callable) { return $callable($di); });
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function parameter($name, $value)
    {
        $this->container[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->container[$name];
    }
}