<?php

namespace Phizzl\Deployee\Logger;


interface LoggerInjectableInterface
{
    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}