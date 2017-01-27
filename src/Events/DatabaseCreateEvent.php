<?php


namespace Deployee\Events;


use Deployee\Database\DatabaseManager;

class DatabaseCreateEvent extends AbstractEvent
{
    const NAME = "DatabaseCreateEvent";

    /**
     * @var DatabaseManager
     */
    private $database;

    /**
     * @param DatabaseManager $database
     */
    public function setDatabaseManager(DatabaseManager $database){
        $this->database = $database;
    }

    /**
     * @return DatabaseManager
     */
    public function getDatabaseManager(){
        return $this->database;
    }
}