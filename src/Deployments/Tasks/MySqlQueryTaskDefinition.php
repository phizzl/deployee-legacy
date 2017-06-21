<?php

namespace Phizzl\Deployee\Deployments\Tasks;


class MySqlQueryTaskDefinition extends AbstractTaskDefinition
{
    /**
     * @var string
     */
    private $query;

    /**
     * MySqlQueryTaskDefinition constructor.
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function define()
    {
        return ["query" => $this->query];
    }

}