<?php


namespace Deployee\Contexts;


interface ContextContainingInterface
{
    /**
     * @return Context
     */
    public function getContext();
}