<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Exception;

/**
 * Class InvalidCriteriaException
 * @package Vemid\ProjectOne\Common\Exception
 */
class InvalidCriteriaException extends \RuntimeException implements ExceptionInterface
{
    public static function invalidExpression()
    {
        return new self("'expression' should be an array of single expression rules");
    }

    public static function invalidExpressionRule()
    {
        return new self("Expression rule should be an array containing 'fld', 'op' and 'val' parameters");
    }

    public static function invalidOrderings()
    {
        return new self("'orderings' should be an array");
    }

    public static function invalidFirstResult()
    {
        return new self("'first_result' should be non-negative integer");
    }

    public static function invalidMaxResults()
    {
        return new self("'max_results' should be integer greater than zero");
    }

    public static function invalidPage()
    {
        return new self("'page' should be integer greater than zero");
    }
}
