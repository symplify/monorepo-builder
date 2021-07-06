<?php

namespace MonorepoBuilder20210706\Psr\Log;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait LoggerAwareTrait
{
    /**
     * The logger instance.
     *
     * @var LoggerInterface|null
     */
    protected $logger;
    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(\MonorepoBuilder20210706\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
