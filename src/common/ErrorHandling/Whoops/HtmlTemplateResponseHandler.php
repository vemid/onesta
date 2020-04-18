<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\ErrorHandling\Whoops;

use Mezzio\Template\TemplateRendererInterface;
use Whoops\Handler\Handler;

/**
 * Class HtmlTemplateResponseHandler
 * @package Vemid\ProjectOne\Common\ErrorHandling\Whoops
 */
final class HtmlTemplateResponseHandler extends Handler
{
    /** @var TemplateRendererInterface */
    private $renderer;

    /**
     * @var string
     */
    private $template;

    /**
     * HtmlTemplateResponseHandler constructor.
     * @param TemplateRendererInterface $renderer
     * @param string $template
     */
    public function __construct(TemplateRendererInterface $renderer, string $template)
    {
        $this->renderer = $renderer;
        $this->template = $template;
    }

    public function handle()
    {
        echo $this->renderer->render($this->template, [
            'error' => $this->getException()->getMessage(),
        ]);

        return Handler::QUIT;
    }

    public function contentType(): string
    {
        return 'text/html';
    }
}
