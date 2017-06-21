<?php


namespace Phizzl\Deployee\Db;


interface DbInterface
{
    /**
     * @return bool
     */
    public function connect();

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function select($sql, array $params = []);

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function getOne($sql, array $params = []);

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function execute($sql, array $params = []);
}