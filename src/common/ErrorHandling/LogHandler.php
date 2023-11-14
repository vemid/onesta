<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling;

use Psr\Log\LoggerInterface;
use Vemid\ProjectOne\Common\Exception\DontLogInterface;

/**
 *
 */
class LogHandler
{
    private LoggerInterface $logger;

    /**
     * @var array
     */
    private array $dontLog;

    public function __construct(LoggerInterface $logger, array $dontLog = [])
    {
        $this->logger = $logger;
        $this->dontLog = array_merge($dontLog, [DontLogInterface::class]);
    }

    public function __invoke(\Throwable $error)
    {
        if (!$this->shouldLog($error)) {
            return;
        }

        $this->logger->error(
            $error->getMessage(),
            ['exception' => $error]
        );
    }

    /**
     * @param $error
     * @return bool
     */
    private function shouldLog($error) : bool
    {
        foreach ($this->dontLog as $exceptionType) {
            if ($error instanceof $exceptionType) {
                return false;
            }
        }

        return true;
    }
}
