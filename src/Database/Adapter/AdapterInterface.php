<?php


namespace Deployee\Database\Adapter;


interface AdapterInterface
{
    /**
     * @param array $conf
     */
    public function setConfiguration(array $conf);
}