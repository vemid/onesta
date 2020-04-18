<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Exception\Formatter;
use Whoops\Handler\Handler;

/**
 * Class JSendResponseHandler
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class JSendResponseHandler extends Handler
{
    /**
     * @var bool
     */
    private $includeErrorDetails;

    public function __construct(bool $includeErrorDetails)
    {
        $this->includeErrorDetails = $includeErrorDetails;
    }

    public function handle()
    {
        $exception = $this->getException();

        $response = [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ];

        if ($this->includeErrorDetails) {
            $response['data'] = Formatter::formatExceptionAsDataArray($this->getInspector(), true);
        }

        echo json_encode($response, \defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0);

        return Handler::QUIT;
    }

    public function contentType(): string
    {
        return 'application/json';
    }
}
