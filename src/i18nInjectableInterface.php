<?php


namespace Phizzl\Deployee;


interface i18nInjectableInterface
{
    /**
     * @param i18n $i18n
     * @return void
     */
    public function setI18n(i18n $i18n);
}