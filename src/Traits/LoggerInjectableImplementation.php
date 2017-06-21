<?php


namespace Phizzl\Deployee\Traits;


use Phizzl\Deployee\Logger\LoggerInterface;

trait LoggerInjectableImplementation
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    protected function logger()
    {
        return $this->logger;
    }
}