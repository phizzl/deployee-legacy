<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\Db\DbInterface;

trait DbInjectableImplementation
{
    /**
     * @var DbInterface
     */
    private $db;

    /**
     * @param DbInterface $db
     */
    public function setDb(DbInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @return DbInterface
     */
    protected function db()
    {
        return $this->db;
    }
}