<?php


namespace Deployee\Db\Adapter;


interface TableInterface extends \Phizzl\QueryGenerate\Tables\TableInterface
{
    /**
     * Executes the creation of the table
     */
    public function create();

    /**
     * Executes the update of the table
     */
    public function update();

    /**
     * @return bool
     */
    public function exists();
}