<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use \Vemid\ProjectOne\Common\Exception\ClientErrorInterface;
use \Exception;
use Whoops\Handler\Handler;

/**
 * Class SuppressErrorDetailsHandler
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class SuppressErrorDetailsHandler extends Handler
{
    /**
     * @var array
     */
    private $options = [
        'error_message' => 'An unexpected error has occurred',
        'http_status_code' => 500,
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function handle()
    {
        if ($this->shouldSuppress()) {
            $this->suppress();
        }

        return self::DONE;
    }

    private function shouldSuppress() : bool
    {
        return !($this->getException() instanceof ClientErrorInterface);
    }

    private function suppress(): void
    {
        $suppressedError = new \Exception($this->options['error_message']);

        $this->setException($suppressedError);

        $this->getRun()->sendHttpCode($this->options['http_status_code']);
    }
}
