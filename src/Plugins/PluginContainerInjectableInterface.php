<?php

namespace Phizzl\Deployee\Plugins;


use Phizzl\Deployee\Di\DiContainer;

interface PluginContainerInjectableInterface
{
    /**
     * @param DiContainer $plugins
     */
    public function setPluginContainer(DiContainer $plugins);
}