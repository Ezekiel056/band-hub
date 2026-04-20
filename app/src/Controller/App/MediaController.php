<?php

namespace App\Controller\App;

use App\Entity\BackingTrack;
use App\Enum\FileUploadType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MediaController extends AppController
{
    #[Route('/app/backingtrack/{id}', name: 'serve_backing_track', requirements: ['backingTrackId' => '\d+'], methods: ['GET'])]
    public function serveBackingTrack(
        BackingTrack $backingTrack): BinaryFileResponse
    {
        $song = $backingTrack->getSong();
        $this->denyAccessUnlessGranted('song.view',$song);



        $path = $this->getParameter(FileUploadType::BackingTracks->value) . $backingTrack->getFileName();
        if (file_exists($path)) {
            return new BinaryFileResponse($path);
        } else {
            throw $this->createNotFoundException('Fichier introuvable');
        }
    }
}
