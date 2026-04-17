<?php

namespace App\Controller\App;

use App\Entity\Song;
use App\Entity\SongLink;
use App\Form\SongLinkType;
use App\Form\SongType;
use App\Service\YoutubeLinksResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\AppMenuTabs;
final class SongController extends AppController
{
    #[Route('app/song/create', name: 'app_song_create', methods: ['POST','GET']) ]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($song);
            $entityManager->flush();

            $response = $this->redirect($referer ?? $this->generateUrl('app_repertoire'));
            $response->headers->set('Turbo-Visit-Control', 'reload');
            return $response;
        }

        return $this->render('app/song/_create.html.twig', [
            'form' => $form,

        ]);
    }

    #[Route('app/song/{id}', name: 'app_song', options:['selected_tab' => AppMenuTabs::Repertoire] ,methods: ['GET'])]

    public function view(Song $song): Response
    {
        $this->denyAccessUnlessGranted('song.view', $song);
        return $this->render('app/song/song.html.twig', [
            'song' => $song,
            'pageTitle' => 'Fiche chanson',
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

            $response = $this->redirect($referer ?? $this->generateUrl('app_song', ['id' => $song->getId()]));
            $response->headers->set('Turbo-Visit-Control', 'reload');
            return $response;
        }

        return $this->render('app/song/_addLink.html.twig' , [
            'form' => $form,
            'song_id' => $song->getId(),
        ]);
    }
}
