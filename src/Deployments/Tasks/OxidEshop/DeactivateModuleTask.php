<?php


namespace Deployee\Deployments\Tasks\OxidEshop;

use Deployee\Deployments\Tasks\CommandLine\ExecuteInternalCommandTask;
use Deployee\Descriptions\TaskDescription;

class DeactivateModuleTask extends ExecuteInternalCommandTask
{
    /**
     * @var string
     */
    private $moduleident;

    /**
     * ActivateModuleTask constructor.
     * @param string $moduleident
     */
    public function __construct($moduleident){
        $this->moduleident = $moduleident;
        parent::__construct("oxid:module {$moduleident} deactivate");
    }

    /**
     * @inheritdoc
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Deaktiviere das Modul \"{$this->moduleident}\""
        );

        return $desc;
    }
}