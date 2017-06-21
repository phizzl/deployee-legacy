<?php

namespace Phizzl\Deployee\Configuration;


class StaticConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var string
     */
    private $config;

    /**
     * YamlConfigurationLoader constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function load()
    {
        return $this->config;
    }
}