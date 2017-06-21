<?php

namespace Phizzl\Deployee\Plugins;


interface PluginInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return void
     */
    public function initialize();
}