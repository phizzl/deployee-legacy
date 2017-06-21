<?php


namespace Phizzl\Deployee\Deployments\Tasks;


class DirectoryRemoveTaskDefinition extends AbstractFilesystemTaskDefinition
{
    /**
     * @var bool
     */
    private $recursive;

    /**
     * DirectoryRemoveTaskDefinition constructor.
     * @param string $file
     * @param bool $recursive
     */
    public function __construct($file, $recursive = false)
    {
        parent::__construct($file, '', '', '');
        $this->recursive = $recursive;
    }

    /**
     * @return array
     */
    public function define()
    {
        return array_merge(parent::define(), ['recursive' => $this->recursive]);
    }
}