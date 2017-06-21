<?php


namespace Phizzl\Deployee\Configuration;


interface ConfigurationInterface
{
    /**
     * @param ConfigurationLoaderInterface $loader
     * @return void
     */
    public function setLoader(ConfigurationLoaderInterface $loader);

    /**
     * @return mixed
     */
    public function initialize();

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * @return array
     */
    public function all();
}