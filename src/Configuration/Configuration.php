<?php


namespace Phizzl\Deployee\Configuration;


class Configuration implements ConfigurationInterface
{
    /**
     * @var ConfigurationLoaderInterface
     */
    private $loader;

    /**
     * @var array
     */
    private $config;

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->config = [];
    }

    /**
     * @param ConfigurationLoaderInterface $loader
     */
    public function setLoader(ConfigurationLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Initialize by getting the config from the loader
     */
    public function initialize()
    {
        $this->config = $this->loader->load();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->config;
    }
}