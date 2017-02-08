<?php


namespace Deployee\Db\Adapter;


interface AdapterInterface
{
    /**
     * @param string $name
     * @param array $options
     * @return TableInterface
     */
    public function table($name, array $options = array());

    /**
     * @param string $sql
     * @return mixed
     */
    public function getOne($sql);

    /**
     * @param string $sql
     * @return mixed
     */
    public function query($sql);

    /**
     * @param TableInterface $table
     * @return mixed
     */
    public function executeTable(TableInterface $table);

    /**
     * @param TableInterface $table
     */
    public function executeData(TableInterface $table);

    /**
     * @param string $sql
     * @return mixed
     */
    public function execute($sql);
}