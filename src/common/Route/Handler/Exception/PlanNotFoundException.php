<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Route\Handler\Exception;

use Arbor\Exception\ProvidesHttpStatusCodeInterface;

class PlanNotFoundException extends \InvalidArgumentException implements
    ExceptionInterface,
    ProvidesHttpStatusCodeInterface
{
    public static function forIdentifier(string $identifier)
    {
        return new self(sprintf('Plan with ID: %s can not be retrieved.', $identifier));
    }

    public function getHttpStatusCode(): int
    {
        return 404;
    }
}
