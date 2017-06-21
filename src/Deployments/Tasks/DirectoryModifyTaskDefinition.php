<?php


namespace Phizzl\Deployee\Deployments\Tasks;


class DirectoryModifyTaskDefinition extends AbstractFilesystemTaskDefinition
{
    /**
     * @var bool
     */
    private $recursive;

    /**
     * DirectoryModifyTaskDefinition constructor.
     * @param string $file
     * @param bool $recursive
     * @param string $owner
     * @param string $group
     * @param string $permissions
     */
    public function __construct($file, $recursive = false, $owner = '', $group = '', $permissions = '')
    {
        parent::__construct($file, $owner, $group, $permissions);
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