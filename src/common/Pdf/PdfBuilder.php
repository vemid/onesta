<?php

declare(strict_types=1);

namespace  Vemid\ProjectOne\Common\Pdf;

use Vemid\ProjectOne\Common\Pdf\Renderer\RendererInterface;
use Vemid\ProjectOne\Common\Pdf\Renderer\WkHtmlToPdf;

/**
 * Class Builder
 * @package Vemid\ProjectOne\Common\Pdf
 */
class PdfBuilder implements PdfBuilderInterface
{
    /** @var RendererInterface */
    private $renderer;

    /**
     * Builder constructor.
     * @param RendererInterface|null $renderer
     */
    public function __construct(RendererInterface $renderer = null)
    {
        if (!$renderer) {
            $renderer = new WkHtmlToPdf();
        }

        $this->renderer = $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function setRenderer(RendererInterface $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function render($html, $orientation = 'portrait')
    {
        return $this->renderer->create($html, $orientation);
    }

    /**
     * {@inheritdoc}
     */
    public function setPageSize($width, $height)
    {
        $this->renderer->setPageSize($width, $height);
    }

    /**
     *
     * {@inheritdoc}
     */
    public function setMargins($marginTop, $marginRight, $marginBottom, $marginLeft)
    {
        $this->renderer->setMargins($marginTop, $marginRight, $marginBottom, $marginLeft);
    }
}
