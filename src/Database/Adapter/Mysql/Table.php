<?php


namespace Deployee\Database\Adapter\Mysql;


use Phinx\Db\Adapter\AdapterInterface;

class Table extends \Phinx\Db\Table
{
    /**
     * @var array
     */
    private $defaultOptions = array('id' => false);

    /**
     * Table constructor.
     * @param string $name
     * @param array $options
     * @param AdapterInterface|null $adapter
     */
    public function __construct($name, array $options = array(), AdapterInterface $adapter = null){
        $options = array_merge($this->defaultOptions, $options);
        parent::__construct($name, $options, $adapter);
    }

    /**
     * @extend \Phinx\Db\Table::addColumn
     * @param \Phinx\Db\Table\Column|string $columnName
     * @param null $type
     * @param array $options
     * @return \Phinx\Db\Table
     */
    public function addColumn($columnName, $type = null, $options = array()){
        if(is_string($columnName)){
            $columnName = $this->createColumnObject($columnName, $type, $options);
        }

        return parent::addColumn($columnName, $type, $options);
    }

    /**
     * @extend \Phinx\Db\Table::changeColumn
     * @param string $columnName
     * @param \Phinx\Db\Table\Column|string $newColumnType
     * @param array $options
     * @return \Phinx\Db\Table
     */
    public function changeColumn($columnName, $newColumnType, $options = array()){
        if(is_string($columnName)){
            $columnName = $this->createColumnObject($columnName, $newColumnType, $options);
        }

        return parent::changeColumn($columnName, $newColumnType, $options);
    }

    /**
     * @param string $columnName
     * @param string $type
     * @param array $options
     * @return Column
     */
    private function createColumnObject($columnName, $type, array $options){
        $column = new Column();
        $column->setName($columnName);
        $column->setType($type);
        $column->setOptions($options);
        return $column;
    }

    /**
     * @return string
     */
    public function getCreateSql(){
        return $this->getAdapter()->getCreateSql($this);
    }
}