<?php


namespace Deployee\Database\Adapter;




use Deployee\Database\Adapter\Mysql\Table;
use Phinx\Db\Table\Column;

class MysqlAdapter extends \Phinx\Db\Adapter\MysqlAdapter
{
    /**
     * @var bool
     */
    private $disableExecutingQueries;

    /**
     * @var string
     */
    private $lastQuery = "";

    /**
     * @param Table $table
     * @return mixed
     */
    public function getCreateSql(Table $table){
        $this->disableExecutingQueries = true;
        $this->createTable($table);
        $this->disableExecutingQueries = false;
        $lastQuery = $this->lastQuery;
        $this->lastQuery = "";

        return $lastQuery;
    }

    /**
     * @param Table $table
     * @return string
     */
    public function getChangeSql(Table $table){
        $this->disableExecutingQueries = true;
        $this->changeTable($table);
        $this->disableExecutingQueries = false;
        $lastQuery = $this->lastQuery;
        $this->lastQuery = "";

        return $lastQuery;
    }

    /**
     * @param Table $table
     * @return string
     */
    public function getUpdateSql(Table $table){
        $this->disableExecutingQueries = true;
        $this->updateTable($table, false);
        $this->disableExecutingQueries = false;
        $lastQuery = $this->lastQuery;
        $this->lastQuery = "";

        return $lastQuery;
    }

    /**
     * @param Table $table
     */
    public function changeTable(Table $table){
        $table->change();
    }

    /**
     * @param Table $table
     * @param bool $resetAfterUpdate
     */
    public function updateTable(Table $table, $resetAfterUpdate = true){
        $table->update($resetAfterUpdate);
    }

    /**
     * @param string $sql
     * @return int|null
     */
    public function execute($sql){
        $this->lastQuery .= "$sql\n";
        return $this->disableExecutingQueries ? null : parent::execute($sql);
    }

    /**
     * @param Column $column
     * @return string
     */
    public function getColumnSqlDefinition(Column $column){
        return parent::getColumnSqlDefinition($column) .
            ($column->getCollation() ? " COLLATE '{$column->getCollation()}'" : "");
    }

    /**
     * @param string $sql
     * @param array $driverOptions
     * @return \PDOStatement
     */
    public function prepare($sql, array $driverOptions = array()){
        return $this->getConnection()->prepare($sql, $driverOptions);
    }

    /**
     * Prevent Phinx from creating a schema table
     * @return bool
     */
    public function createSchemaTable(){

    }
}