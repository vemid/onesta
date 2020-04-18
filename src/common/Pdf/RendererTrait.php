<?php

declare(strict_types=1);

namespace  Vemid\ProjectOne\Common\Pdf;

/**
 * Trait RendererTrait
 * @package Vemid\ProjectOne\Common\Pdf
 */
trait RendererTrait
{
    /** @var string */
    protected $pageHeight = '400mm';

    /** @var string */
    protected $pageWidth = '305mm';

    /** @var string */
    protected $marginTop = '5mm';

    /** @var string */
    protected $marginRight = '5mm';

    /** @var string */
    protected $marginBottom = '0mm';

    /** @var string */
    protected $marginLeft = '5mm';

    /**
     * @return string
     */
    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    /**
     * @param string $marginBottom
     */
    public function setMarginBottom($marginBottom)
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @return string
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * @param string $marginLeft
     */
    public function setMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @return string
     */
    public function getMarginRight()
    {
        return $this->marginRight;
    }

    /**
     * @param string $marginRight
     */
    public function setMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @return string
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * @param string $marginTop
     */
    public function setMarginTop($marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @return string
     */
    public function getPageHeight()
    {
        return $this->pageHeight;
    }

    /**
     * @param string $pageHeight
     */
    public function setPageHeight($pageHeight)
    {
        $this->pageHeight = $pageHeight;
    }

    /**
     * @return string
     */
    public function getPageWidth()
    {
        return $this->pageWidth;
    }

    /**
     * @param string $pageWidth
     */
    public function setPageWidth($pageWidth)
    {
        $this->pageWidth = $pageWidth;
    }
}
