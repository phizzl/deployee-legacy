<?php


namespace Deployee\Database\Adapter\Mysql;


class Column extends \Phinx\Db\Table\Column
{
    /**
     * @var bool
     */
    private $autoincrement;

    /**
     * @var string
     */
    private $collation;

    /**
     * @return array
     */
    public function getValidOptions(){
        return array_merge(parent::getValidOptions(), array(
            'autoincrement',
            'collation'
        ));
    }

    /**
     * @param bool $autoincrement
     * @return $this
     */
    public function setAutoincrement($autoincrement){
        $this->autoincrement = $autoincrement;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIdentity(){
        return $this->autoincrement ? true : parent::isIdentity();
    }

    /**
     * @return string
     */
    public function getCollation(){
        return $this->collation;
    }

    /**
     * @param string $collation
     * @return $this
     */
    public function setCollation($collation){
        $this->collation = $collation;
        return $this;
    }
}