<?php

namespace Phizzl\Deployee\Di;


use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;

class DiInjector implements DiInjectorInterface
{
    use DiContainerInjectableImplementation;

    /**
     * @var array
     */
    private $mapping;

    /**
     * DiInjector constructor.
     */
    public function __construct()
    {
        $this->mapping = [];
    }

    /**
     * @param string $instanceName
     * @param string $method
     * @param string|mixed $serviceName
     */
    public function addInjectionDefinition($instanceName, $method, $serviceName)
    {
        if(!isset($this->mapping[$instanceName])){
            $this->mapping[$instanceName] = [];
        }

        $this->mapping[$instanceName][] = [$method, $serviceName];
    }

    /**
     * @param object $object
     */
    public function injectDependencies($object)
    {
        foreach($this->mapping as $instanceOf => $injectionConfigurations) {
            if($object instanceof $instanceOf){
                $this->injectContainerDependencies($object, $injectionConfigurations);
            }
        }
    }

    /**
     * @param object $object
     * @param array $injectionConfigurations
     */
    private function injectContainerDependencies($object, array $injectionConfigurations)
    {
        foreach($injectionConfigurations as $injectionConfiguration){
            $inject = is_string($injectionConfiguration[1])
                ? $this->container->get($injectionConfiguration[1])
                : $injectionConfiguration[1];
            call_user_func_array(
                [$object, $injectionConfiguration[0]],
                [$inject]
            );
        }
    }
}