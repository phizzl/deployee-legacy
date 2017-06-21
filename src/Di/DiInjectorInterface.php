<?php

namespace Phizzl\Deployee\Di;


interface DiInjectorInterface extends DiContainerInjectableInterface
{
    /**
     * @param string $instanceName
     * @param string $method
     * @param string $serviceName
     * @return void
     */
    public function addInjectionDefinition($instanceName, $method, $serviceName);

    /**
     * @param object $object
     * @return void
     */
    public function injectDependencies($object);
}