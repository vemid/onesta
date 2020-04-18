<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Entity\Entity\User;
use Zend\Diactoros\UploadedFile;

/**
 * Class Avatar
 * @package Vemid\ProjectOne\Common\Helper
 */
class Avatar
{
    private $uploadPath = APP_PATH . '/var/uploads/';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager = null)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserAvatar(User $user)
    {
        if (is_file($this->uploadPath . $user->getAvatar())) {
            return '/uploads/' . $user->getAvatar();
        }

        return '/img/avatar.png';
    }

    public function uploadAvatar(UploadedFile $file, User $user)
    {
        $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

        $filePath = $this->uploadPath . substr(sha1((string)mt_rand()), 0, 8) . '.' . $ext;
        $file->moveTo($filePath);

        if (is_file($currentAvatar = $this->uploadPath . $user->getAvatar())) {
            @unlink($currentAvatar);
        }

        $this->resizeImage(300, $filePath);

        $user->setAvatar(basename($filePath));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
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
}
