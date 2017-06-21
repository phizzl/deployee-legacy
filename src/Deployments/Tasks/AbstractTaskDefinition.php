<?php


namespace Phizzl\Deployee\Deployments\Tasks;


use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Environment\EnvironmentInjectableInterface;
use Phizzl\Deployee\Logger\LoggerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use Phizzl\Deployee\Traits\EnvironmentInjectableImplementation;
use Phizzl\Deployee\Traits\LoggerInjectableImplementation;

abstract class AbstractTaskDefinition
implements TaskDefinitionInterface, DiContainerInjectableInterface, LoggerInjectableInterface, EnvironmentInjectableInterface
{
    use DiContainerInjectableImplementation;
    use LoggerInjectableImplementation;
    use EnvironmentInjectableImplementation;

    /**
     * @inheritdoc
     */
    abstract public function define();
}