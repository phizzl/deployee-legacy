<?php


namespace Deployee\Db\Adapter;


interface AdapterInterface
{
    /**
     * @param string $name
     * @param array $options
     * @return TableInterface
     */
    public function table($name, array $options = array());
}