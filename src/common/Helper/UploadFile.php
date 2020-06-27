<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Helper;

use Zend\Diactoros\UploadedFile;

/**
 * Class UploadFile
 * @package Vemid\ProjectOne\Common\Helper
 */
class UploadFile
{
    /** @var string */
    private $uploadPath = APP_PATH . '/var/uploads/';

    /**
     * @param UploadedFile $file
     * @param bool $resize
     * @return string
     */
    public function uploadFile(UploadedFile $file, $resize = false): string
    {
        $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

        $filePath = $this->uploadPath . substr(sha1((string)mt_rand()), 0, 8) . '.' . $ext;

        if (is_file($filePath)) {
            @unlink($filePath);
        }

        $file->moveTo($filePath);

        if ($resize && $this->isImage($filePath)) {
            $this->resizeImage(300, $filePath);
        }

        return basename($filePath);
    }

    public function resizeImage($maxDim, $fileName)
    {
        list($width, $height, $type, $attr) = getimagesize($fileName);

        if ( $width > $maxDim || $height > $maxDim ) {
            $targetFilename = $fileName;
            $ratio = $width/$height;
            if( $ratio > 1) {
                $newWidth = $maxDim;
                $newHeight = $maxDim/$ratio;
            } else {
                $newWidth = $maxDim*$ratio;
                $newHeight = $maxDim;
            }

            $src = imagecreatefromstring(file_get_contents($fileName));
            $dst = imagecreatetruecolor((int)$newWidth, (int)$newHeight);

            imagecopyresampled($dst, $src, 0, 0, 0, 0, (int)$newWidth, (int)$newHeight, (int)$width, (int)$height);
            imagedestroy($src);
            imagepng($dst, $targetFilename);
            imagedestroy($dst);
        }
    }

    /**
     * @param $filename
     * @return string
     */
    private function getMimeType($filename)
    {
        return mime_content_type($filename);
    }

    /**
     * @param $filename
     * @return bool
     */
    private function isImage($filename)
    {
        return stripos($filename, 'image') !== false;
    }
}
