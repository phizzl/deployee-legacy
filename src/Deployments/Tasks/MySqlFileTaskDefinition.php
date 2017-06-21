<?php

namespace Phizzl\Deployee\Deployments\Tasks;


class MySqlFileTaskDefinition extends AbstractTaskDefinition
{
    /**
     * @var string
     */
    private $file;

    /**
     * MySqlFileTaskDefinition constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return array
     */
    public function define()
    {
        return ["file" => $this->file];
    }

}