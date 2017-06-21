<?php


namespace Phizzl\Deployee\Logger;

interface LoggerInterface
{
    /**
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = []);

    /**
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = []);

    /**
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = []);
}