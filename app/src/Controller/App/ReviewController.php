<?php

namespace App\Controller\App;

use App\Document\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AppController
{
    #[Route('/app/review/create', name: 'app_review_create', methods: ['POST', 'GET'])]
    public function new(Request $request, DocumentManager $documentManager, ReviewRepository $reviewRepository): Response
    {
        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_settings_profile');
        }

        $userId = $this->getUser()->getId();
        $review = $reviewRepository->findOneByUserId($userId);
        $isEdit = $review !== null;
        $review ??= new Review();

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUserId($userId);
            $documentManager->persist($review);
            $documentManager->flush();

            $this->addFlash('success', $isEdit ? 'Votre avis a été modifié.' : 'Merci pour votre avis !');

            return $this->TurboRefreshRoute('app_settings');
        }

        return $this->render('app/review/_create.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
        ]);
    }

    #[Route('/app/review/delete', name: 'app_review_delete', methods: ['POST'])]
    public function delete(DocumentManager $documentManager, ReviewRepository $reviewRepository): Response
    {
        $review = $reviewRepository->findOneByUserId($this->getUser()->getId());

        if ($review !== null) {
            $documentManager->remove($review);
            $documentManager->flush();
            $this->addFlash('success', 'Votre avis a été supprimé.');
        }

        return $this->redirectToRoute('app_settings');
    }
}
