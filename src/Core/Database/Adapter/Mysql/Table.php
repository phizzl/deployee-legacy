<?php


namespace Deployee\Core\Database\Adapter\Mysql;


use Deployee\Core\Database\Adapter\MysqlAdapter;
use Deployee\Core\Database\Adapter\TableInterface;

class Table extends \Phizzl\QueryGenerate\Tables\Table implements TableInterface
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
     * @return $this
     */
    public function update(){
        $this->setIsCreated(true);
        $this->adapter->executeTable($this);
        return $this;
    }

    /**
     * @return $this
     */
    public function create(){
        $this->setIsCreated(false);
        $this->adapter->executeTable($this);
        return $this;
    }

    /**
     * @return bool
     */
    public function exists(){
        try {
            $this->adapter
                ->query("SELECT 1 FROM {$this->getName()} LIMIT 1")
                ->execute();
        }
        catch(\PDOException $e){
            return false;
        }

        return true;
    }

    /**
     * @retun $this
     */
    public function saveData(){
        $this->adapter->executeData($this);
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateSql(){
        $this->setIsCreated(false);
        return $this->generate();
    }

    /**
     * @return string
     */
    public function getUpdateSql(){
        $this->setIsCreated(true);
        return $this->generate();
    }
}