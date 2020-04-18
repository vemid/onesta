<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Whoops;

use Psr\Log\LoggerInterface;
use Vemid\ProjectOne\Common\Exception\DontLogInterface;

/**
 * @author Nikola Posa <nikola.posa@arbor-education.com>
 */
class LogHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $dontLog = [];

    public function __construct(
        LoggerInterface $logger,
        array $dontLog = []
    ) {
        $this->logger = $logger;
        $this->dontLog = array_merge($dontLog, [DontLogInterface::class]);
    }

    public function __invoke($error)
    {
        /* @var $error \Throwable */

        if (!$this->shouldLog($error)) {
            return;
        }

        $this->logger->error(
            $error->getMessage(),
            [
                'exception' => $error,
            ]
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
