<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\Di\DiContainer;

trait PluginContainerInjectableImplementation
{
    /**
     * @var DiContainer
     */
    private $plugins;

    /**
     * @param DiContainer $plugins
     */
    public function setPluginContainer(DiContainer $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return DiContainer
     */
    protected function plugins()
    {
        return $this->plugins;
    }
}