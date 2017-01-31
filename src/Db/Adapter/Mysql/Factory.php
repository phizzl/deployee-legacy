<?php


namespace Deployee\Db\Adapter\Mysql;

use Deployee\Db\Adapter\MysqlAdapter;

class Factory extends \Phizzl\QueryGenerate\Factory\Factory
{
    /**
     * @var MysqlAdapter
     */
    private $adapter;

    /**
     * @param MysqlAdapter $adapter
     */
    public function setAdapter(MysqlAdapter $adapter){
        $this->adapter = $adapter;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Table
     */
    public function getTable($name, array $options = array()){
        $table = new Table($this);
        $table->setName($name);
        $table->setOptions($options);
        $table->setAdapter($this->adapter);

        return $table;
    }
}