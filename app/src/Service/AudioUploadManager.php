<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Enum\FileUploadType;

class AudioUploadManager extends UploadManager
{

    public function ManageUploadedFile(UploadedFile $file, FileUploadType $fileUploadType, array $options = []) : string {
        $this->HandleUpload($file, $fileUploadType);
        return $this->filename;
    }
}
