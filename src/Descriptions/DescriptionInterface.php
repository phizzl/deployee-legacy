<?php


namespace Deployee\Descriptions;


interface DescriptionInterface
{
    const LANG_DE = "DE";

    const LANG_EN = "EN";

    /**
     * @param $lang
     * @param $description
     * @return $this
     */
    public function describeInLang($lang, $description);

    /**
     * @param string $lang
     * @return null
     */
    public function getDescription($lang);
}