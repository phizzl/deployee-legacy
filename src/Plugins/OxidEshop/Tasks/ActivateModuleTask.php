<?php


namespace Deployee\Plugins\OxidEshop\Tasks;

use Deployee\Deployments\Tasks\CommandLine\ExecuteInternalCommandTask;
use Deployee\Descriptions\TaskDescription;

class ActivateModuleTask extends ExecuteInternalCommandTask
{
    /**
     * @var string
     */
    private $moduleident;

    /**
     * @var string|null
     */
    private $shopId;

    /**
     * ActivateModuleTask constructor.
     * @param string $moduleident
     * @param null $shopId
     */
    public function __construct($moduleident, $shopId = null){
        $this->moduleident = $moduleident;
        $this->shopId = $shopId;
        parent::__construct("oxid:module {$moduleident} activate" . ($this->shopId ? " --shopid={$this->shopId}" : ""));
    }

    /**
     * @inheritdoc
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Aktiviere das Modul \"{$this->moduleident}\"" . ($this->shopId ? " im Shop \"{$this->shopId}\"" : "")
        );

        return $desc;
    }
}