<?php


namespace Phizzl\Deployee\Deployments\Tasks;


class CallTaskDefinition extends AbstractTaskDefinition
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * SimpleTaskDefinition constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function define()
    {
        if($this->callback === null){
            throw new \InvalidArgumentException("Callback cannot be undefined");
        }

        return ["callback" => $this->callback];
    }
}