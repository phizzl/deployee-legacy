<?php

namespace Phizzl\Deployee\Filesystem;


use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;

class PathHelper implements DiContainerInjectableInterface
{
    use DiContainerInjectableImplementation;

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->container()->get('_basepath');
    }

    /**
     * @return string
     */
    public function getDeploymentPath()
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . $this->container()->get('config')->get('deploy')['path'];
    }

    /**
     * @return string
     */
    public function getDeploymentFilesPath()
    {
        return $this->getDeploymentPath() . DIRECTORY_SEPARATOR . 'deployments';
    }

    /**
     * @return string
     */
    public function getEnvironmentsPath()
    {
        return $this->getDeploymentPath() . DIRECTORY_SEPARATOR . 'env';
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->getDeploymentPath() . DIRECTORY_SEPARATOR . 'assets';
    }
}