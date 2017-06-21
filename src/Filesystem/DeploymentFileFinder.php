<?php

namespace Phizzl\Deployee\Filesystem;


use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;

class DeploymentFileFinder extends Finder implements DiContainerInjectableInterface
{
    use DiContainerInjectableImplementation;

    /**
     * @return $this
     */
    public function findDeploymentFiles()
    {
        /* @var PathHelper $pathHelper */
        $pathHelper = $this->container()->get('filesystem.paths');
        return $this
            ->depth(0)
            ->files()
            ->name('Deploy_*.php')
            ->sortByName()
            ->in([$pathHelper->getDeploymentFilesPath()]);
    }
}