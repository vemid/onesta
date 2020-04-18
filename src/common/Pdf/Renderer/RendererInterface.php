<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Pdf\Renderer;

/**
 * Interface RendererInterface
 * @package Vemid\ProjectOne\Common\Pdf\Renderer
 */
interface RendererInterface
{

    /**
     * Creates PDF document from the content
     *
     * @param string $content
     * @param string $orientation
     * @return string
     */
    public function create($content, $orientation);


    /**
     * Sets page size
     *
     * @param string $width
     * @param string $height
     */
    public function setPageSize($width, $height);


    /**
     * Sets documents margins
     *
     * @param string $marginTop
     * @param string $marginRight
     * @param string $marginBottom
     * @param string $marginLeft
     */
    public function setMargins($marginTop, $marginRight, $marginBottom, $marginLeft);
}
