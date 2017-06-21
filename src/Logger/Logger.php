<?php


namespace Phizzl\Deployee\Logger;

class Logger implements LoggerInterface
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $instanceId;

    /**
     * Logger constructor.
     */
    public function __construct()
    {
        $this->logger = new \Monolog\Logger('Deployee');
        $this->instanceId = uniqid();
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->logger->addDebug($message, array_merge($context, ["instance" => $this->instanceId]));
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->logger->addInfo($message, array_merge($context, ["instance" => $this->instanceId]));
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = [])
    {
        $this->logger->addAlert($message, array_merge($context, ["instance" => $this->instanceId]));
    }
}