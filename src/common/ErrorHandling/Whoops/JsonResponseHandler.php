<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Handler\Handler;

/**
 * Class JsonResponseHandler
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class JsonResponseHandler extends Handler
{
    public function handle()
    {
        $exception = $this->getException();

        $response = [
            'errors' => [
                [
                    'message' => $exception->getMessage(),
                    'code'    => $exception->getCode(),
                ],
            ],
        ];

        echo json_encode($response, \defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0);

        return Handler::QUIT;
    }

    public function contentType(): string
    {
        return 'application/json';
    }
}
