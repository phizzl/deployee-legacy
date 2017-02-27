<?php

class Deploy_1486665369_6615_OxidModuleDeactivate extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->plugin('oxid')->deactivateModule("invoicepdf");
    }
}