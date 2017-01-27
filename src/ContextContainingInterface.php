<?php


namespace Deployee;


interface ContextContainingInterface
{
    /**
     * @return Context
     */
    public function getContext();
}