<?php

namespace Deployee\Plugins\OxidEshop;

use Deployee\Deployments\AbstractDeployment;
use Deployee\Plugins\AbstractPlugin;
use Deployee\Plugins\OxidEshop\Tasks\ActivateModuleTask;
use Deployee\Plugins\OxidEshop\Tasks\DeactivateModuleTask;

class OxidEshopPlugin extends AbstractPlugin
{
    /**
     * @param string $moduleident
     * @return AbstractDeployment
     */
    public function activateModule($moduleident){
        return $this->deployment->addTask(new ActivateModuleTask($moduleident));
    }

    /**
     * @param string $moduleident
     * @return AbstractDeployment
     */
    public function deactivateModule($moduleident){
        return $this->deployment->addTask(new DeactivateModuleTask($moduleident));
    }
}