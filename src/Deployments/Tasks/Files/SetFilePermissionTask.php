<?php


namespace Deployee\Deployments\Tasks\Files;


use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class SetFilePermissionTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var int
     */
    protected $permission;

    /**
     * @var bool
     */
    protected $recursive;

    /**
     * SetFilePermissionTask constructor.
     * @param string $filepath
     * @param int $permission
     * @param bool $recursive
     */
    public function __construct($filepath, $permission, $recursive = false)
    {
        $this->filepath = $filepath;
        $this->permission = $permission;
        $this->recursive = $recursive;
        $this->getContext()
            ->set('file', $filepath)
            ->set('permission', "$permission");
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        $objectPermission = new ObjectPermission($this->permission, $this->recursive);
        $objectPermission->applyTo($this->filepath);
    }

    /**
     * @inheritdoc
     */
    public function undo(){

    }

    /**
     * @return TaskDescription
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Die Dateirechte der Datei \"{$this->filepath}\" werden auf \"{$this->permission}\" gesetzt"
        );

        return $desc;
    }
}