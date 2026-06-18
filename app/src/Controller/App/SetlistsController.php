<?php

namespace App\Controller\App;

use App\Entity\SetlistModel;
use App\Enum\AppMenuTabs;
use App\Form\SetlistModelType;
use App\Repository\SetlistModelRepository;
use App\Entity\SetlistModelSong;
use App\Repository\SetlistModelSongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SetlistsController extends AppController
{
    #[Route('app/setlists', name: 'app_setlists', options: ['selected_tab' => AppMenuTabs::Setlists])]
    public function index(SetlistModelRepository $setlistModelRepository): Response
    {
        $setlists = $setlistModelRepository->findByBand($this->getCurrentBand());
        return $this->render('app/setlists/setlists.html.twig', [
            'setlists' => $setlists,
            'pageTitle' => 'Setlists',
        ]);
    }

    #[Route('app/setlists/create', name: 'app_setlist_create', methods: ['POST','GET']) ]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_setlists');
        }

        $setlist = new SetlistModel();
        $setlist->setColor('#ECE4FD');
        $form = $this->createForm(SetlistModelType::class, $setlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $setlist->setBand($this->getCurrentBand());
            $setlist->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($setlist);
            $entityManager->flush();

            return $this->TurboRefreshRoute('app_setlists');
        }

        return $this->render('app/setlists/_create.html.twig', [
            'form' => $form,

        ]);
    }

    #[Route('app/setlists/{id}', name: 'app_setlist_view', options: ['selected_tab' => AppMenuTabs::Setlists], methods: ['GET']) ]
    public function view(SetlistModel $setList,Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('setlist.view', $setList);
        return $this->render('app/setlists/setlist.html.twig', [
            'setlist' => $setList,
            'pageTitle' => 'Setlists',
        ]);
    }

    #[Route('app/setlists/{id}/edit', name: 'app_setlist_edit', methods: ['GET', 'POST'])]
    public function edit(SetlistModel $setList, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_setlist_view', ['id' => $setList->getId()]);
        }

        $this->denyAccessUnlessGranted('setlist.view', $setList);

        $form = $this->createForm(SetlistModelType::class, $setList);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Setlist modifiée avec succès');
            return $this->TurboRefreshRoute('app_setlist_view', ['id' => $setList->getId()]);
        }

        return $this->render('app/setlists/_edit.html.twig', [
            'form' => $form,
            'setlist' => $setList,
        ]);
    }

    #[Route('app/setlists/{id}/delete', name: 'app_setlist_delete', methods: ['POST'])]
    public function delete(SetlistModel $setList, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('setlist.view', $setList);

        $entityManager->remove($setList);
        $entityManager->flush();

        $this->addFlash('success', 'Setlist supprimée');
        return $this->redirectToRoute('app_setlists');
    }

    #[Route('app/setlists/{setlist}/songs/{song}/delete', name: 'app_setlist_song_delete', methods: ['POST'])]
    public function deleteSong(SetlistModel $setlist, SetlistModelSong $song, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('setlist.view', $setlist);

        if ($song->getSetlistModel() === $setlist) {
            $entityManager->remove($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_setlist_view', ['id' => $setlist->getId()]);
    }

    #[Route('app/setlists/{id}/reorder', name: 'app_setlist_reorder', methods: ['PATCH'])]
    public function reorder(SetlistModel $setList, Request $request, SetlistModelSongRepository $songRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('setlist.view', $setList);

        $data = json_decode($request->getContent(), true);

        foreach ($data['positions'] ?? [] as $item) {
            $song = $songRepository->find($item['id']);
            if ($song && $song->getSetlistModel() === $setList) {
                $song->setPosition($item['position']);
            }
        }

        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
