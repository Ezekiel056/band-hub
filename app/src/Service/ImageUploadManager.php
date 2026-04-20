<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Enum\FileUploadType;

class ImageUploadManager extends UploadManager
{

    public function ManageUploadedFile(UploadedFile $file, FileUploadType $fileUploadtype, array $options = []) : string
    {
        $this->HandleUpload($file, $fileUploadtype);

        $thumbnailSize = $options['thumbnailSize'] ?? null;
        if ($thumbnailSize) {
        $this->imageManager->decodePath($this->uploadDir . $this->filename)
        ->cover($thumbnailSize,$thumbnailSize)
        ->save($this->uploadDir . 'thumbnails/' . $thumbnailSize.'x'.$thumbnailSize . '_'. $this->filename);
        }
        return $this->filename;

    }
}
