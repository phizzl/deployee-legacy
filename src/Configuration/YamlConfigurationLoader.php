<?php

namespace Phizzl\Deployee\Configuration;


use Symfony\Component\Yaml\Yaml;

class YamlConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * YamlConfigurationLoader constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function load()
    {
        if(!is_file($this->file)
            || !is_readable($this->file)){
            throw new \InvalidArgumentException("Config file could not be found");
        }

        return Yaml::parse(file_get_contents($this->file));
    }

}