<?php


namespace Deployee\Core\Contexts;


class Context
{
    /**
     * @var array
     */
    private $contents;

    /**
     * Context constructor.
     * @param array $values
     */
    public function __construct($values = array()){
        $this->contents = $values;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value){
        $this->contents[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name){
        return isset($this->contents[$name]);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name){
        return $this->has($name) ? $this->contents[$name] : null;
    }

    /**
     * @return array
     */
    public function getContents(){
        return $this->contents;
    }
}