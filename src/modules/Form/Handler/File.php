<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;

/**
 * Class File
 * @package Vemid\ProjectOne\Form\Handler
 */
class File extends AbstractHandler
{
    public function download($fileName, FileManager $uploadFile)
    {
        try {
            return $uploadFile->downloadFile($fileName);
        } catch (\Exception $e) {
            $this->messageBag->pushFlashMessage($e->getMessage(), null, Builder::WARNING);
        }
    }
}