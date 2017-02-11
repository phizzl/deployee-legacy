<?php

namespace Deployee\Core\Database;


use Deployee\Core\Database\Adapter\AdapterInterface;

class DbManager
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter){
        $this->adapter = $adapter;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter(){
        return $this->adapter;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Adapter\TableInterface
     */
    public function table($name, array $options = array()){
        return $this->adapter->table($name, $options);
    }

    /**
     * @param string $sql
     * @param array|null $vars
     * @return mixed
     */
    public function getOne($sql, array $vars = null){
        return $this->adapter->getOne($sql, $vars);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function execute($sql){
        return $this->adapter->execute($sql);
    }
}