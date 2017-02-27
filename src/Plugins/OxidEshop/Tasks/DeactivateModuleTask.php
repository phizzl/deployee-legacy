<?php


namespace Deployee\Plugins\OxidEshop\Tasks;

use Deployee\Deployments\Tasks\CommandLine\ExecuteInternalCommandTask;
use Deployee\Descriptions\TaskDescription;

class DeactivateModuleTask extends ExecuteInternalCommandTask
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
     * DeactivateModuleTask constructor.
     * @param string $moduleident
     * @param null $shopId
     */
    public function __construct($moduleident, $shopId = null){
        $this->moduleident = $moduleident;
        $this->shopId = $shopId;
        parent::__construct("oxid:module {$moduleident} deactivate" . ($this->shopId ? " --shopid={$this->shopId}" : ""));
    }

    /**
     * @inheritdoc
     */
    public function getDescription(){
        $desc = parent::getDescription();
        $desc->describeInLang(
            TaskDescription::LANG_DE,
            "Deaktiviere das OXID eShop Modul \"{$this->moduleident}\"" . ($this->shopId ? " im Shop \"{$this->shopId}\"" : "")
        );

        return $desc;
    }
}