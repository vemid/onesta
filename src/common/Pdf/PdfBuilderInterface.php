<?php

declare(strict_types=1);

namespace  Vemid\ProjectOne\Common\Pdf;

use Vemid\ProjectOne\Common\Pdf\Renderer\RendererInterface;

/**
 * Interface BuilderInterface
 * @package Vemid\ProjectOne\Common\Pdf
 */
interface PdfBuilderInterface
{
    /**
     * @param string $html
     * @param string $orientation
     * @return string
     */
    public function render($html, $orientation = 'portrait');

    /**
     * @param mixed $width
     * @param mixed $height
     */
    public function setPageSize($width, $height);

    /**
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer): void;

    /**
     * @param string $marginTop
     * @param string $marginRight
     * @param string $marginBottom
     * @param string $marginLeft
     */
    public function setMargins($marginTop, $marginRight, $marginBottom, $marginLeft);
}
