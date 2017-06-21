<?php


namespace Phizzl\Deployee\Deployments\Tasks;


class CommentTaskDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $comment;

    /**
     * CommentTaskDefinition constructor.
     * @param string $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return array
     */
    public function define()
    {
        return ["comment" => $this->comment];
    }
}