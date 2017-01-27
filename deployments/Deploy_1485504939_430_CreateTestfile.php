<?php

class Deploy_1485504939_430_CreateTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');
        $this->createFile(__DIR__ . '/test.txt', 'This is my deployment!');
    }
}