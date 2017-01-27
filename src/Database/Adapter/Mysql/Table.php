<?php


namespace Deployee\Database\Adapter\Mysql;


class Table
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $dirtyOptions;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var array
     */
    private $dirtyColumns;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $sqlQueries;

    /**
     * Table constructor.
     * @param \PDO $pdo
     * @param SchemaTool $schemaTool
     * @param string $tableName
     * @param array $options
     */
    public function __construct(\PDO $pdo, SchemaTool $schemaTool, $tableName, array $options = array()){
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->dirtyOptions = $options;
        $this->schemaTool = $schemaTool;
        $this->dirtyColumns = array();
        $this->columns = array();
        $this->sqlQueries = array();
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->tableName;
    }

    /**
     * @return bool
     */
    public function exists(){
        $sql = "SHOW COLUMNS FROM {$this->tableName}";
        $query = $this->pdo->query($sql);
        if(!$query || !count($query->fetchAll())){
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addColumn($name, $type, array $options = array()){
        $this->dirtyColumns[$name] = array('type' => $type, 'options' => $options);
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(){
        return array_merge($this->columns, $this->dirtyColumns);
    }

    /**
     * @return array
     */
    public function getOptions(){
        return $this->dirtyOptions;
    }

    /**
     * @return $this
     */
    public function create(){
        array_unshift($this->sqlQueries, $this->schemaTool->getCreateSql($this));
        return $this->execute();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function insert(array $data){
        $this->sqlQueries[] = $this->schemaTool->getInsertStatement($this, $data);
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function execute(){
        foreach($this->sqlQueries as $query){
            if($this->pdo->exec($query) === false){
                throw new \Exception(print_r($this->pdo->errorInfo(), 1));
            }
        }

        return $this;
    }
}