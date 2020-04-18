<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Pdf\Renderer;

use Vemid\ProjectOne\Common\Pdf\RendererTrait;

/**
 * Class TcPdf
 * @package Vemid\ProjectOne\Common\Pdf\Renderer
 */
class TcPdf implements RendererInterface
{

    use RendererTrait;

    /**
     * Creates PDF document from the content
     *
     * @param string $content
     * @param string $orientation
     * @return string
     */
    public function create($content, $orientation)
    {
        if ($orientation === 'landscape') {
            $orientation = 'L';
        } else {
            $orientation = 'P';
        }

        $tcpdf = new \TCPDF($orientation);
        $tcpdf->SetCreator(PDF_CREATOR);
        $tcpdf->SetAuthor('Bebakids');
        $tcpdf->SetTitle('');
        $tcpdf->SetMargins((float)$this->getMarginLeft(), (float)$this->getMarginTop(), (float)$this->getMarginRight());
        $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $tcpdf->AddPage();
        $tcpdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);

        return $tcpdf->Output($content, 'S');
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
