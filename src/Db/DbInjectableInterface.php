<?php


namespace Phizzl\Deployee\Db;


interface DbInjectableInterface
{
    /**
     * @param DbInterface $db
     * @return void
     */
    public function setDb(DbInterface $db);
}