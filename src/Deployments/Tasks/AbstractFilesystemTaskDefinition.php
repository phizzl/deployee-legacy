<?php


namespace Phizzl\Deployee\Deployments\Tasks;


abstract class AbstractFilesystemTaskDefinition extends AbstractTaskDefinition
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $permissions;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var string
     */
    private $group;


    /**
     * AbstractFilesystemTaskDefinition constructor.
     * @param string $file
     * @param string $permissions
     * @param string $owner
     * @param string $group
     */
    public function __construct($file, $owner = '', $group = '', $permissions = '')
    {
        $this->file = $file;
        $this->permissions = $permissions;
        $this->owner = $owner;
        $this->group = $group;
    }

    /**
     * @return array
     */
    public function define()
    {
        return [
            'file' => $this->file,
            'permissions' => $this->permissions,
            'owner' => $this->owner,
            'group' => $this->group
        ];
    }
}