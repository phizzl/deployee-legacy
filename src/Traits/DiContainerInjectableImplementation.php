<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\Di\DiContainerInterface;

trait DiContainerInjectableImplementation
{
    /**
     * @var DiContainerInterface
     */
    private $container;

    /**
     * @param DiContainerInterface $container
     */
    public function setContainer(DiContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return DiContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function get($name)
    {
        return $this->container()->get($name);
    }
}