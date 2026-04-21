<?php

namespace App\Controller\App;

use App\Entity\BackingTrack;
use App\Entity\Song;
use App\Enum\FileUploadType;
use App\Form\BackingTrackType;
use App\Service\AudioUploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BackingtrackController extends AppController
{

    #[Route('app/backingtrack/add/{id}', name: 'app_backingtrack_add' ,methods: ['GET', 'POST'])]
    public function addSongBackingTrack(Song $song,Request $request, EntityManagerInterface $entityManager, AudioUploadManager $audioManager): Response
    {

        if ($request->isMethod('GET') && !$request->headers->has('Turbo-Frame')) {
            return $this->redirectToRoute('app_song', ['id' => $song->getId()]);

        }



        $backingTrack= new BackingTrack();
        $form = $this->createForm(BackingTrackType::class, $backingTrack);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('song.view', $song);
            $file = $form->get('fileName')->getData();
            $filename = $audioManager->ManageUploadedFile($file, FileUploadType::BackingTracks);
            $backingTrack->setSong($song);
            $backingTrack->setFileName($filename);
            $entityManager->persist($backingTrack);
            $entityManager->flush();

            $this->addFlash('success','Backing track ajouté avec success');

            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_add_backingtrack.html.twig' , [
            'form' => $form,
            'song_id' => $song->getId(),
        ]);
    }
    #[Route('app/backingtrack/{id}/delete', name: 'app_backingtrack_delete' ,methods:['POST'])]
    public function DeleteBackingTrack(BackingTrack $backingtrack,Request $request, EntityManagerInterface $entityManager): Response
    {

        $song = $backingtrack->getSong();
        $this->denyAccessUnlessGranted('song.view', $song);

        $fileName = $this->getParameter(FileUploadType::BackingTracks->value) . $backingtrack->getFileName();
        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $entityManager->remove($backingtrack);
        $entityManager->flush();

        $this->addFlash('success','Backing track supprmé avec succèes');

        return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
    }

    #[Route('app/backingtrack/{id}/edit', name: 'app_backingtrack_edit' ,methods:['GET','POST'])]
    public function EditBackingTrack(BackingTrack $backingtrack,Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = $backingtrack->getSong();
        $this->denyAccessUnlessGranted('song.view', $song);


        $form = $this->createForm(BackingTrackType::class, $backingtrack,['edit_mode' =>true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($backingtrack);
            $entityManager->flush();

            $this->addFlash('success','Backingtrack modifié avec success');

            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_edit_backingtrack.html.twig' , [
            'form' => $form,
            'backingtrack_id' => $backingtrack->getId(),
        ]);
    }
}
