<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Filter;

/**
 * Class Humanize
 * @package Vemid\ProjectOne\Common\Filter
 */
class Humanize implements FilterInterface
{

    protected $_rules = array(
        '/([a-z])([A-Z])/' => '\1 \2',
        '/[\s-_]+/' => ' '
    );

    /**
     * @param mixed $value
     * @return mixed|string|string[]|null
     */
    public function filter($value)
    {
        $result = strval($value);

        foreach ($this->_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
            }
        }

        return $result;
    }
}
