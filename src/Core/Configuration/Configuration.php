<?php


namespace Deployee\Core\Configuration;


class Configuration
{
    /**
     * @var array
     */
    private $parameter;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * Configuration constructor.
     * @param array $parameter
     */
    public function __construct(array $parameter){
        $this->parameter = $parameter;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name){
        return isset($this->parameter[$name]);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function get($name, $default = null){
        return $this->has($name) ? $this->parameter[$name] : $default;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment){
        $this->environment = $environment;
    }

    /**
     * @return mixed
     * @throws \Exception
     * @return Environment
     */
    public function getEnvironment(){
        return $this->environment;
    }
}