<?php


namespace Deployee\Skel;


use Deployee\ContainerAwareInterface;
use Deployee\DIContainer;

class DeploymentSkel implements ContainerAwareInterface
{
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @param $name
     * @param $ticket
     * @return int
     */
    public function create($name, $ticket){
        $deploymentPath = $this->container['config']->getEnvironment()->getDeploymentPath();
        $className = "Deploy_" . time() . '_' . rand(0,9999) . "_$name";
        $filePath = $deploymentPath . "/{$className}.php";
        $replaces = array(
            '#CLASSNAME#' => $className,
            '#TICKET#' => $ticket ? "\$this->context->set('ticket', '$ticket');\n" : null
        );

        return file_put_contents($filePath, str_replace(array_keys($replaces), $replaces, $this->getSkel()))
            ? $filePath
            : false;
    }

    /**
     * @return string
     */
    public function getSkel(){
        return <<<EOF
<?php

class #CLASSNAME# extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        #TICKET#
    }
}
EOF;

    }
}