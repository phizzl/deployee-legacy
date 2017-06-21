<?php


namespace Phizzl\Deployee\Descriptor;


interface DescriptorFormatterInjectableInterface
{
    /**
     * @param DescriptorFormatterInterface $descriptorFormatter
     * @return mixed
     */
    public function setDescriptorFormatter(DescriptorFormatterInterface $descriptorFormatter);
}