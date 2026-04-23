<?php

namespace App\Controller\App;

use App\Entity\Song;
use App\Entity\SongLink;
use App\Enum\FileUploadType;
use App\Form\LyricsEditType;
use App\Form\OriginalSongType;
use App\Form\SongLinkType;
use App\Form\SongType;
use App\Service\AudioUploadManager;
use App\Service\YoutubeLinksResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\AppMenuTabs;

final class SongController extends AppController
{

    #[Route('app/song/{id}', name: 'app_song', requirements: ['id' => '\d+'], options:['selected_tab' => AppMenuTabs::Repertoire] ,methods: ['GET'])]
    public function view(Song $song): Response
    {
        $this->denyAccessUnlessGranted('song.view', $song);
        return $this->render('app/song/song.html.twig', [
            'song' => $song,
            'pageTitle' => $song->getTitle() .' - '. $song->getArtist()->getName(),
        ]);
    }

    #[Route('app/song/create', name: 'app_song_create', methods: ['POST','GET']) ]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($song);
            $entityManager->flush();


            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_create.html.twig', [
            'form' => $form,

        ]);
    }


    #[Route('app/song/{id}/edit', name: 'app_song_edit', methods: ['POST','GET']) ]
    public function edit(Song $song,Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
        }

        $this->denyAccessUnlessGranted('song.view', $song);
        dump($song);
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($song);
            $entityManager->flush();

            $this->addFlash('success','Informtions modifiées avec succès');
            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_edit.html.twig', [
            'form' => $form,
            'songId' => $song->getId(),
        ]);
    }


    #[Route('app/song/{id}/links/add', name: 'app_song_add_link' ,methods: ['GET', 'POST'])]
    public function addSongLink(Song $song,Request $request, EntityManagerInterface $entityManager, YoutubeLinksResolver $youtubeLinks): Response
    {
        if ($request->isMethod('GET') && !$request->headers->has('Turbo-Frame')) {
            return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
        }

        $this->denyAccessUnlessGranted('song.view', $song);
        $link = new SongLink();
        $form = $this->createForm(SongLinkType::class, $link);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $link->setSong($song);
            $link->setLink($youtubeLinks->resolve($link->getLink()));
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_addLink.html.twig' , [
            'form' => $form,
            'song_id' => $song->getId(),
        ]);
    }

    #[Route('app/song/{id}/original-song/add', name: 'app_song_add_original_song' ,methods: ['GET', 'POST'])]
    public function addSongOriginalSong(Song $song,Request $request, EntityManagerInterface $entityManager, AudioUploadManager $audioManager): Response
    {

        $requestType = $request->query->get('request');

        /* Security checks*/
            if ($this->isTurboFrameRequest($request)) {
                return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
            }

            if ($song->getId() == 0) {
                $this->DenyAcces();
            }

            $this->denyAccessUnlessGranted('song.view', $song);
        /**/



        $form = $this->createForm(OriginalSongType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fileName')->getData();
            $filename = $audioManager->ManageUploadedFile($file, FileUploadType::BackingTracks);

            // Si la song possède deja un morceau original, on supprime le fichier actuel.
            $currentFile = $song->getOriginalSongFileName();
            if ($currentFile != '') {
                unlink($this->getParameter(FileUploadType::BackingTracks->value) . $currentFile);
            }

            $song->setOriginalSongFileName($filename);
            $entityManager->persist($song);
            $entityManager->flush();
            dump($requestType);
            $this->addFlash('success','Morceau original' . ($requestType != null ? ' ajouté ' : ' remplacé ') . 'avec success');

            return $this->TurboRefreshRoute('app_song', ['id' => $song->getId()]);
        }

        return $this->render('app/song/_add_original_song.html.twig' , [
            'form' => $form,
            'song_id' => $song->getId(),
            'request' => $requestType
        ]);
    }


    #[Route('app/song/{id}/delete-original-song/', name: 'app_song_original_song_delete' ,methods: ['POST'])]
    public function deleteSongOriginalVersion(Song $song, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('song.view', $song);

        if ($song->getOriginalSongFileName()  != '' && file_exists($this->getParameter(FileUploadType::BackingTracks->value).$song->getOriginalSongFileName())) {
            unlink($this->getParameter(FileUploadType::BackingTracks->value).$song->getOriginalSongFileName());
        }
        $song->setOriginalSongFileName('');

        $entityManager->persist($song);
        $entityManager->flush();

        $this->addFlash('success','Version originale supprimée avec succès');
        return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
    }



    #[Route('app/song/links/delete/{id}', name: 'app_song_link_delete' ,methods: ['POST'])]
    public function deleteSongLink(SongLink $songLink, EntityManagerInterface $entityManager): Response
    {

        $song = $songLink->getSong();
        $this->denyAccessUnlessGranted('song.view', $song);

        $entityManager->remove($songLink);
        $entityManager->flush();

        return $this->redirectToRoute('app_song', ['id' => $song->getId()]);
    }

    #[Route('app/song/{id}/lyrics', name: 'app_song_lyrics' , options:['selected_tab' => AppMenuTabs::Repertoire],methods: ['GET'])]
    public function lyrics(Song $song, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('song.view', $song);

       return $this->render('app/lyrics/lyrics.html.twig' , [
           'song' => $song,
           'pageTitle' => $song->getTitle() . ' - paroles',


       ]);
    }

    #[Route('app/song/{id}/lyrics/edit', name: 'app_song_lyrics_edit' , options:['selected_tab' => AppMenuTabs::Repertoire],methods: ['GET','POST'])]
    public function EditLyrics(Song $song, Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_song_lyrics', ['id' => $song->getId()]);
        }
        $this->denyAccessUnlessGranted('song.view', $song);

        $form = $this->createForm(LyricsEditType::class, $song);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($song);
            $entityManager->flush();

            return $this->redirectToRoute('app_song_lyrics', ['id' => $song->getId()]);
        }
        return $this->render('app/lyrics/_edit.html.twig' , [
            'lyrics' => $song->getLyrics(),
            'song_id' => $song->getId(),
            'form' => $form
        ]);
    }

}
