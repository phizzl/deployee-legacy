<?php


namespace Deployee\Database\Adapter;


use Deployee\Database\Adapter\Mysql\SchemaTool;
use Deployee\Database\Adapter\Mysql\Table;

class MysqlAdapter extends AbstractAdapter
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @param array $conf
     */
    public function setConfiguration(array $conf){
        $this->pdo = new \PDO("mysql:={$conf['host']};dbname={$conf['name']}", $conf['user'], $conf['password']);
    }

    /**
     * @param string $name
     * @param array $options
     * @return Table
     */
    public function table($name, array $options = array()){
        return new Table($this->pdo, $this->getSchemaTool(), $name, $options);
    }

    /**
     * @return SchemaTool
     */
    public function getSchemaTool(){
        if($this->schemaTool === null){
            $this->schemaTool = new SchemaTool($this->pdo);
        }

        return $this->schemaTool;
    }

    /**
     * @return \PDO
     */
    public function getPDO(){
        return $this->pdo;
    }
}