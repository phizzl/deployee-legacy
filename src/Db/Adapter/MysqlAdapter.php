<?php


namespace Deployee\Db\Adapter;

use Deployee\ContainerAwareInterface;
use Deployee\Db\Adapter\Mysql\Factory;
use Deployee\Db\Adapter\Mysql\Table;
use Deployee\DIContainer;
use Phizzl\QueryGenerate\Drivers\MysqlDriver;
use Phizzl\QueryGenerate\Drivers\MysqlQueryEscape;
use Phizzl\QueryGenerate\QueryGenerator;

class MysqlAdapter implements AdapterInterface, ContainerAwareInterface
{
    /**
     * @var QueryGenerator
     */
    private $generator;

    /**
     * @var DIContainer
     */
    private $container;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param string $name
     * @param array $options
     * @return \Phizzl\QueryGenerate\Tables\TableInterface
     */
    public function table($name, array $options = array()){
        return $this->getQueryGenerator()->table($name, $options);
    }

    /**
     * @return QueryGenerator
     */
    private function getQueryGenerator(){
        if($this->generator !== null){
            return $this->generator;
        }

        $driver = new MysqlDriver();
        $driver->setQueryEscape(new MysqlQueryEscape());
        $factory = new Factory();
        $factory->setDriver($driver);
        $factory->setAdapter($this);

        return $this->generator = new QueryGenerator($factory);
    }

    /**
     * @param DIContainer $container
     */
    public function setContainer(DIContainer $container){
        $this->container = $container;
    }

    /**
     * @return \PDO
     */
    private function getPdo(){
        if($this->pdo !== null){
            return $this->pdo;
        }

        $conf = $this->container['config']->getEnvironment()->getDatabaseConfiguration('mysql');
        $dsn = "mysql:host={$conf['host']};port={$conf['port']};dbname={$conf['name']}"
            . (isset($conf['charset']) ? ";charset={$conf['charset']}" : '');
        $pdo = new \PDO($dsn, $conf['user'], $conf['pass'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));

        if(isset($conf['charset'])){
            $pdo;
        }

        return $this->pdo = $pdo;
    }

    /**
     * @param Table $table
     */
    public function executeTable(Table $table){
        $sql = $table->generate();
        $this->getPdo()->exec($sql);
    }

    /**
     * @param string $sql
     * @return \PDOStatement
     */
    public function query($sql){
        return $this->getPdo()->query($sql);
    }
}