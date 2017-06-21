<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\Descriptor\DescriptorFormatterInterface;

trait DescriptorFormatterInjectableImplementation
{
    /**
     * @var DescriptorFormatterInterface
     */
    private $descriptorFormatter;

    /**
     * @param DescriptorFormatterInterface $descriptorFormatter
     */
    public function setDescriptorFormatter(DescriptorFormatterInterface $descriptorFormatter)
    {
        $this->descriptorFormatter = $descriptorFormatter;
    }

    /**
     * @return DescriptorFormatterInterface
     */
    protected function formatter()
    {
        return $this->descriptorFormatter;
    }
}