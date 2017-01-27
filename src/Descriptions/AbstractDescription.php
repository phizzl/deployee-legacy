<?php

namespace Deployee\Descriptions;


abstract class AbstractDescription implements DescriptionInterface
{
    /**
     * @var array
     */
    private $descriptions;

    public function __construct(){
        $this->descriptions = array();
    }

    /**
     * @param $lang
     * @param $description
     * @return $this
     */
    public function describeInLang($lang, $description){
        $this->descriptions[$lang] = $description;
        return $this;
    }

    /**
     * @param string $lang
     * @return null
     */
    public function getDescription($lang){
        return isset($this->descriptions[$lang]) ? $this->descriptions[$lang] : null;
    }
}