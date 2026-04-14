<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Enum\FileUploadType;
use Intervention\Image\Interfaces\ImageManagerInterface as InterventionImage;

class ImageManager
{
    public function __construct(
        private InterventionImage $imageManager,
        private ParameterBagInterface $params,
    )
    {

    }



    public function ManageUploadedFile(UploadedFile $file, FileUploadType $fileUploadtype, ?int $thumbnailSize = null) {
        $uploadDir = $this->params->get($fileUploadtype->value);
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($uploadDir, $filename);

        if ($thumbnailSize) {
            $this->imageManager->decodePath($uploadDir . $filename)
            ->cover($thumbnailSize,$thumbnailSize)
            ->save($uploadDir . 'thumbnails/' . $thumbnailSize.'x'.$thumbnailSize . '_'. $filename);
        }
        return $filename;

    }
}
