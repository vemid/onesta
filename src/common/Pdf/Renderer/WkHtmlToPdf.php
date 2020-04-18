<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Pdf\Renderer;

use Vemid\ProjectOne\Common\Pdf\RendererTrait;
use Knp\Snappy\Pdf as Snappy;

/**
 * Class WkHtmlToPdf
 * @package Vemid\ProjectOne\Common\Pdf\Renderer
 */
class WkHtmlToPdf implements RendererInterface
{
    use RendererTrait;

    /**
     * @param string $content
     * @param string $orientation
     * @return string
     * @throws \InvalidArgumentException
     */
    public function create($content, $orientation = 'portrait')
    {
        $snappy = new Snappy();
        if (stripos(PHP_OS_FAMILY, 'linux') !== false) {
            $snappy->setBinary(APP_PATH . 'vendor/bin/wkhtmltopdf-amd64');
        } else {
            $snappy->setBinary('/usr/local/bin/wkhtmltopdf');
        }

        $snappy->setOption('page-width', $this->pageWidth);
        $snappy->setOption('page-width', $this->pageWidth);
        $snappy->setOption('page-height', $this->pageHeight);
        $snappy->setOption('margin-top', $this->marginTop);
        $snappy->setOption('margin-bottom', $this->marginBottom);
        $snappy->setOption('margin-right', $this->marginRight);
        $snappy->setOption('margin-left', $this->marginLeft);
        $snappy->setOption('print-media-type', true);
        $snappy->setOption('orientation', $orientation);
        $snappy->setOption('enable-forms',true);
        $snappy->setOption('disable-smart-shrinking', true);
        $snappy->setTimeout(1200);

        return $snappy->getOutputFromHtml($content);
    }

    /**
     * Sets page size
     *
     * @param string $width
     * @param string $height
     */
    public function setPageSize($width, $height)
    {
        $this->setPageWidth($width);
        $this->setPageHeight($height);
    }

    /**
     * Sets documents margins
     *
     * @param string $marginTop
     * @param string $marginRight
     * @param string $marginBottom
     * @param string $marginLeft
     */
    public function setMargins($marginTop, $marginRight, $marginBottom, $marginLeft)
    {
        $this->setMarginTop($marginTop);
        $this->setMarginRight($marginRight);
        $this->setMarginBottom($marginBottom);
        $this->setMarginLeft($marginLeft);
    }
}
