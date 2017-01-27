<?php


namespace Deployee\Deployments\Tasks\Files;


use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class SetFileGroupTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var string
     */
    protected $group;

    /**
     * @var bool
     */
    protected $recursive;

    /**
     * SetFilePermissionTask constructor.
     * @param string $filepath
     * @param string $group
     * @param bool $recursive
     */
    public function __construct($filepath, $group, $recursive = false)
    {
        $this->filepath = $filepath;
        $this->group = $group;
        $this->recursive = $recursive;
        $this->getContext()
            ->set('file', $filepath)
            ->set('group', $group);
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        $objectPermission = new ObjectOwner(null, $this->group, $this->recursive);
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
            "Die Gruppe der Datei \"{$this->filepath}\" wird auf \"{$this->group}\" gesetzt"
        );

        return $desc;
    }
}