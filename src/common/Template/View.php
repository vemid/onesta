<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Template;

/**
 * Class View
 * @package Vemid\ProjectOne\Common\Template
 */
class View
{
    /** @var string|null  */
    private $template;

    /**
     * View constructor.
     * @param string|null $template
     * @param array|null $params
     */
    public function __construct(string $template = null, array $params = null)
    {
        $this->template = ['template' => $template, 'params' => $params];
    }

    /**
     * @return string
     */
    public function getTemplate(): array
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @param array|null $params
     */
    public function setTemplate(string $template, array $params = null): void
    {
        $this->template = ['template' => $template, 'params' => $params];
    }
}
