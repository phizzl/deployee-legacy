<?php

class Deploy_1486665687_9817_OxidModuleActivate extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->plugin('oxid')->activateModule("invoicepdf");
    }
}