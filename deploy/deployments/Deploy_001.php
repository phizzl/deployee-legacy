<?php


use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Create temporary directory and put temp file to it
 * @ticket NXS-001
 */
class Deploy_001 extends AbstractDeploymentDefinition
{
    /**
     * @return array
     * @describe Add tmp directory and add new file
     * @ticket NXS-001
     */
    public function define()
    {
        $this->dirCreate($this->path('tmp'));
        $this->fileCreate('tmp' . DIRECTORY_SEPARATOR . 'myfile.txt', 'Awesomecontent');

        return parent::define();
    }
}