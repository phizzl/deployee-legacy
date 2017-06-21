<?php


namespace Phizzl\Deployee\Descriptor;

interface DescriptorFormatterInterface
{
    /**
     * @return string
     */
    public function openDocument();

    /**
     * @return string
     */
    public function closeDocument();

    /**
     * @param string $message
     * @return string
     */
    public function headline($message);

    /**
     * @param string $message
     * @return string
     */
    public function subheadline($message);

    /**
     * @param string $message
     * @return string
     */
    public function write($message);

    /**
     * @param string $message
     * @return string
     */
    public function writeln($message);

    /**
     * @return string
     */
    public function newline();

    /**
     * @param string $message
     * @return mixed
     */
    public function bold($message);

    /**
     * @return string
     */
    public function line();

    /**
     * @param string $message
     * @return string
     */
    public function quote($message);
}