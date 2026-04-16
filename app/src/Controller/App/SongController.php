<?php

namespace App\Controller\App;

use App\Entity\Song;
use App\Form\SongType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SongController extends AbstractController
{
    #[Route('app/song/create', name: 'app_song_create', methods: ['POST','GET'])]
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

    #[Route('app/song/{id}', name: 'app_song', methods: ['GET'])]

    public function view(Song $song): Response
    {
        $this->denyAccessUnlessGranted('song.view', $song);
        return $this->render('app/song/song.html.twig', [
            'song' => $song,
            'pageTitle' => 'Fiche chanson',
        ]);
    }
}
