<?php


namespace Phizzl\Deployee\Environment;


interface EnvironmentInjectableInterface
{
    /**
     * @param EnvironmentInterface $env
     * @return void
     */
    public function setEnvironment(EnvironmentInterface $env);
}