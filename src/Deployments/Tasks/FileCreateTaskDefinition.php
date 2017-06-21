<?php


namespace Phizzl\Deployee\Deployments\Tasks;


class FileCreateTaskDefinition extends AbstractFilesystemTaskDefinition
{
    /**
     * @var string
     */
    private $contents;

    /**
     * FileCreateTaskDefinition constructor.
     * @param string $file
     * @param string $contents
     * @param string $owner
     * @param string $group
     * @param string $permissions
     */
    public function __construct($file, $contents = '', $owner = '', $group = '', $permissions = '')
    {
        parent::__construct($file, $owner, $group, $permissions);
        $this->contents = $contents;
    }

    /**
     * @return array
     */
    public function define()
    {
        return array_merge(parent::define(), ['contents' => $this->contents]);
    }
}