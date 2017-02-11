<?php


namespace Deployee\Core\Contexts;


interface ContextContainingInterface
{
    /**
     * @return Context
     */
    public function getContext();
}