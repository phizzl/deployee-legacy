<?php

namespace Phizzl\Deployee\Db;


class Db implements DbInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Db constructor.
     */
    public function __construct()
    {
        $this->config = [];
    }

    /**
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $this->pdo = $this->createPdo();
        $this->getOneRow('SHOW TABLE STATUS');

        return true;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function select($sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        $return = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $return;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function getOneRow($sql, array $params = [])
    {
        return current($this->select($sql, $params));
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function getOne($sql, array $params = [])
    {
        return current($this->getOneRow($sql, $params));
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function execute($sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        return $statement->execute($params);
    }

    /**
     * @return \PDO
     */
    private function createPdo()
    {
        return new \PDO(
            "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['name']}",
            $this->config['user'],
            $this->config['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }
}