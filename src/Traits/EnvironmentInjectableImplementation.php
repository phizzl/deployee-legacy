<?php


namespace Phizzl\Deployee\Traits;



use Phizzl\Deployee\Environment\EnvironmentInterface;

trait EnvironmentInjectableImplementation
{
    /**
     * @var EnvironmentInterface
     */
    private $env;

    /**
     * @param EnvironmentInterface $env
     */
    public function setEnvironment(EnvironmentInterface $env)
    {
        $this->env = $env;
    }

    /**
     * @return EnvironmentInterface
     */
    protected function env()
    {
        return $this->env;
    }
}