<?php

namespace App\Controller;

use App\Controller\App\AppController;
use App\Entity\Artist;
use App\Enum\FileUploadType;
use App\Form\ArtistType;
use App\Service\ImageUploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AppController
{
    #[Route('/artist/create', name: 'app_artist_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, ImageUploadManager $imageManager): Response
    {

        $form = $this->createForm(ArtistType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = new Artist();

            /** @var UploadedFile $file */
            $file = $form->get('coverFileName')->getData();
            $filename = $imageManager->ManageUploadedFile($file, FileUploadType::ArtistCover,['thumbnailSize'=>128]);

            $artist->setCoverFileName($filename);
            $artist->setName($form->get('name')->getData());
            $artist->setBand($this->getCurrentBand());

            $entityManager->persist($artist);
            $entityManager->flush();
            return $this->redirectToRoute('app_repertoire');

        }

        return $this->render('app/artist/_create.html.twig', [
            'form' => $form,
        ]);
    }
}
