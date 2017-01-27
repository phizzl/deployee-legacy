<?php

class Deploy_1485504996_3113_UpdateTestfile extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'ABC-100');
        $this->updateFile(__DIR__ . '/test.txt', 'This is my updated content!');
        $this->createFile(__DIR__ . '/test2.txt', 'Another test file');
    }
}