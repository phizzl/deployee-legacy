<?php


namespace Deployee\Deployments\Tasks\Files;


use Deployee\Deployments\Tasks\AbstractTask;
use Deployee\Descriptions\TaskDescription;

class SetFileOwnerTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var string
     */
    protected $owner;

    /**
     * @var bool
     */
    protected $recursive;

    /**
     * SetFilePermissionTask constructor.
     * @param string $filepath
     * @param string $owner
     * @param bool $recursive
     */
    public function __construct($filepath, $owner, $recursive = false)
    {
        $this->filepath = $filepath;
        $this->owner = $owner;
        $this->recursive = $recursive;
        $this->getContext()
            ->set('file', $filepath)
            ->set('owner', $owner);
    }

    /**
     * @inheritdoc
     */
    public function execute(){
        $objectPermission = new ObjectOwner($this->owner, null, $this->recursive);
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
            "Der Besitzer der Datei \"{$this->filepath}\" wird auf \"{$this->owner}\" gesetzt"
        );

        return $desc;
    }
}