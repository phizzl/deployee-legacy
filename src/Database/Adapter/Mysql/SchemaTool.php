<?php


namespace Deployee\Database\Adapter\Mysql;


class SchemaTool
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * SchemaTool constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    /**
     * @param Table $table
     * @return string
     */
    public function getCreateSql(Table $table){
        $options = $table->getOptions();
        $columnStatements = array();
        foreach($table->getColumns() as $name => $column){
            $columnStatements[] = $this->createColumnStatement($name, $column);
        }

        $primaryKeyStatement = $this->getPrimaryKey($table);
        $uniqueIndexStatements = $this->getUniqueIndices($table);
        $indexStatements = $this->getIndices($table);

        $createStatement = "CREATE TABLE `{$table->getName()}` (\n";
        $createStatement .= implode(",\n", $columnStatements);
        $createStatement .= strlen($primaryKeyStatement) ? ",\n{$primaryKeyStatement}" : "";
        $createStatement .= count($uniqueIndexStatements) ? ",\n" . implode(",\n", $uniqueIndexStatements) : "";
        $createStatement .= count($indexStatements) ? ",\n" . implode(",\n", $indexStatements) : "";
        $createStatement .= ")\n";
        $createStatement .= isset($options['collation']) ? "COLLATE='{$options['collation']}'\n" : "";
        $createStatement .= isset($options['engine']) ? "ENGINE={$options['engine']}" : "";
        $createStatement .= isset($options['auto_increment']) ? "AUTO_INCREMENT={$options['auto_increment']}" : "";
        $createStatement .= ";";

        return $createStatement;
    }

    /**
     * @param Table $table
     * @return string
     */
    private function getPrimaryKey(Table $table){
        $options = $table->getOptions();
        return isset($options['primary_key'])
            ? "PRIMARY KEY (" . (
                is_array($options['primary_key'])
                    ? implode(', ', $options['primary_key'])
                    : $options['primary_key']
                ) . ")"
            : '';
    }

    /**
     * @param Table $table
     * @return array
     */
    private function getUniqueIndices(Table $table){
        $options = $table->getOptions();
        $indices = array();

        if(!isset($options['unique'])){
            return $indices;
        }

        foreach($options['unique'] as $name => $index){
            $indices[] = "UNIQUE INDEX `{$name}` (" . (is_array($index) ? implode(', ', $index) : $index) .")";
        }

        return $indices;
    }

    /**
     * @param Table $table
     * @return array
     */
    private function getIndices(Table $table){
        $options = $table->getOptions();
        $indices = array();

        if(!isset($options['indices'])){
            return $indices;
        }

        foreach($options['indices'] as $name => $index){
            $indices[] = "INDEX `{$name}` (" . (is_array($index) ? implode(', ', $index) : $index) .")";
        }

        return $indices;
    }

    /**
     * @param string $name
     * @param array $column
     * @return string
     */
    private function createColumnStatement($name, array $column){
        $lengthRequired = array('varchar', 'char', 'tinyint', 'int', 'bigint', 'tinytext');
        $options = $column['options'];

        $statement = "`{$name}` {$column['type']}";

        if(in_array($column['type'], $lengthRequired)){
            if(!isset($options['length'])) {
                throw \Exception("Field \"$name\" requires a length");
            }
            $statement .= "({$options['length']})";
        }

        $statement .= isset($options['signed']) && !$options['signed'] ? " UNSIGNED" : "";
        $statement .= isset($options['nullable']) && $options['nullable'] ? " NULL" : " NOT NULL";
        $statement .= isset($options['auto_increment']) && $options['auto_increment'] ? " AUTO_INCREMENT" : "";
        $statement .= isset($options['default']) ? " DEFAULT {$options['default']}" : "";
        $statement .= isset($options['comment']) ? " COMMENT '{$options['comment']}'" : "";
        $statement .= isset($options['collation']) ? " COLLATE '{$options['collation']}'" : "";

        return $statement;
    }

    /**
     * @param Table $table
     * @param array $data
     * @return string
     */
    public function getInsertStatement(Table $table, array $data){
        $statement = "INSERT INTO {$table->getName()} \n";
        $statement .= "(" . implode(', ', array_keys($data)) . ")\n";
        $statement .= "VALUES(" . implode(', ', $this->quoteArray($data)) . ");";

        return $statement;
    }

    /**
     * @param Table $table
     * @param array $data
     * @param array $where
     * @return string
     */
    public function getUpdateStatement(Table $table, array $data, array $where){
        $statement = "UPDATE {$table->getName()}\n SET ";
        $setStatements = array();
        foreach($this->quoteArray($data) as $column => $value){
            $setStatements[] = "{$column}={$value}";
        }

        $whereConditions = array();
        $conditionMode = isset($where['_MODE']) ? $where['_MODE'] : "AND";
        foreach($this->quoteArray($where) as $column => $value){
            $whereConditions[] = "{$column}={$value}";
        }

        $statement .= implode(', ', $setStatements) . "\n";
        $statement .= count($whereConditions) ? "WHERE " . implode(" {$conditionMode} ", $whereConditions) : "";

        return $statement;
    }

    /**
     * @param array $data
     * @return array
     */
    public function quoteArray(array $data){
        foreach($data as &$value){
            $value = $this->pdo->quote($value);
        }

        return $data;
    }
}