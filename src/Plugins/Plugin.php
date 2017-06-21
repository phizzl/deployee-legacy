<?php

namespace Phizzl\Deployee\Plugins;


use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;

class Plugin implements PluginInterface, DiContainerInjectableInterface
{
    use DiContainerInjectableImplementation;

    public function getId()
    {
        return 'test';
    }


    public function initialize()
    {

    }
}