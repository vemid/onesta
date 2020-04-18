<?php

namespace Vemid\ProjectOne\Common\Route\Handler\Exception;

/**
 * Class FilterNotAllowedException
 * @package Vemid\ProjectOne\Common\Route\Handler\Exception
 */
class FilterNotAllowedException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param string $filterName
     * @return FilterNotAllowedException
     */
    public static function forFilter(string $filterName)
    {
        return new self(sprintf('Filter \'%s\' is not allowed', $filterName));
    }
}