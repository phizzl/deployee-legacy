<?php

namespace Phizzl\Deployee\Deployments\Tasks;


interface TaskDefinitionInterface
{
    /**
     * @return array
     */
    public function define();
}