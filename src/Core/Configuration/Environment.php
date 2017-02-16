<?php

namespace Deployee\Core\Configuration;

class Environment
{
    /**
     * @var array
     */
    protected $environment;

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * Environment constructor.
     * @param array $environment
     */
    public function __construct(array $environment){
        $this->environment = $environment;
    }

    /**
     * @param string|null $name
     * @return array
     */
    public function getDatabaseConfiguration($name = null){
        $database = $this->environment['database'];
        return $name === null ? $database : $database[$name];
    }

    /**
     * @return string
     */
    public function getDeploymentPath(){
        return str_replace('~', getcwd(), $this->environment['deployments']);
    }

    /**
     * @return string
     */
    public function getInstanceId(){
        if($this->instanceId === null){
            $this->instanceId = uniqid(rand(0,99999));
        }
        return $this->instanceId;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get($name){
        return isset($this->environment[$name]) ? $this->environment[$name] : null;
    }
}