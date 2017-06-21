<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\i18n;

trait i18nInjectableImplementation
{
    /**
     * @var i18n
     */
    private $i18n;

    /**
     * @param i18n $i18n
     */
    public function setI18n(i18n $i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * @return i18n
     */
    protected function i18n()
    {
        return $this->i18n;
    }
}