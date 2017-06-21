<?php


use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Remove temp file and temp directory
 * @ticket NXS-001
 */
class Deploy_002 extends AbstractDeploymentDefinition
{
    /**
     * @return array
     * @describe Add tmp directory and add new file
     * @ticket NXS-001
     */
    public function define()
    {
        $this->fileRemove('tmp' . DIRECTORY_SEPARATOR . 'myfile.txt');
        $this->dirRemove($this->path('tmp'));

        return parent::define();
    }
}