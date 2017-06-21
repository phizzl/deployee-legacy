<?php


namespace Phizzl\Deployee\Configuration;


class ConfigMerger
{
    /**
     * @var ConfigurationInterface
     */
    private $config;

    /**
     * ConfigMerger constructor.
     */
    public function __construct()
    {
        $this->config = new Configuration();
    }

    /**
     * @param ConfigurationInterface $config
     */
    public function setConfigurationInstance(ConfigurationInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param ConfigurationInterface $c1
     * @param ConfigurationInterface $c2
     * @return ConfigurationInterface
     */
    public function merge(ConfigurationInterface $c1, ConfigurationInterface $c2)
    {
        $mergedConfig = array_merge($c1->all(), $c2->all());
        $loader = new StaticConfigurationLoader($mergedConfig);
        $config = clone $this->config;
        $config->setLoader($loader);
        $config->initialize();

        return $config;
    }
}