<?php


namespace Phizzl\Deployee\Di;


interface DiContainerInterface
{
    /**
     * @param string $name
     * @param callable $callable
     * @return void
     */
    public function set($name, $callable);

    /**
     * @param string $name
     * @param callable $callable
     * @return void
     */
    public function factory($name, $callable);

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function parameter($name, $value);

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name);
}