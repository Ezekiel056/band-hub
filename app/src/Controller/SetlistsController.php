<?php

namespace App\Controller;

use App\Controller\App\AppController;
use App\Entity\SetlistModel;
use App\Enum\AppMenuTabs;
use App\Form\SetlistModelType;
use App\Repository\SetlistModelRepository;
use Doctrine\ORM\EntityManagerInterface;
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

}
