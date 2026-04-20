<?php

namespace App\Service;

use App\Enum\FileUploadType;
use Intervention\Image\Interfaces\ImageManagerInterface as InterventionImage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class UploadManager
{
    protected $uploadDir = '';
    protected $filename = '';

    public function __construct(
        protected InterventionImage $imageManager,
        protected ParameterBagInterface $params,
    )
    {

    }
    protected function HandleUpload(UploadedFile $file, FileUploadType $fileUploadType) {
        $this->uploadDir = $this->params->get($fileUploadType->value);
        $this->filename = uniqid() . '.' . $file->guessExtension();
        $file->move($this->uploadDir, $this->filename);
    }

    abstract protected function ManageUploadedFile(UploadedFile $file, FileUploadType $fileUploadType,array $options = []);
}
