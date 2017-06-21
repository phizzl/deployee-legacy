<?php

namespace Phizzl\Deployee\Environment;

use Phizzl\Deployee\Configuration\ConfigurationInterface;

interface EnvironmentInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration();
}