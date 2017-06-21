<?php

namespace Phizzl\Deployee\Configuration;


interface ConfigurationLoaderInterface
{
    /**
     * @return array
     */
    public function load();
}