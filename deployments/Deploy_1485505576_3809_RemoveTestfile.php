<?php

class Deploy_1485505576_3809_RemoveTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');

        $this->removeFile(__DIR__ . '/test2.txt');
    }
}