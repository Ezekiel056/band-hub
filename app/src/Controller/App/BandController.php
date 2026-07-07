<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Entity\BandMember;
use App\Entity\User;
use App\Form\BandType;
use App\Service\CurrentBandResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BandController extends AppController
{
    #[Route('/app/band/create', name: 'app_band_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, Security $security, CurrentBandResolver $bandResolver): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createBand($band, $user, $entityManager, $bandResolver);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('app/band/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/app/band/create-modal', name: 'app_band_create_modal', methods: ['GET', 'POST'])]
    public function createModal(Request $request, EntityManagerInterface $entityManager, Security $security, CurrentBandResolver $bandResolver): Response
    {

        if ($this->isTurboFrameRequest($request)) {
            return $this->redirectToRoute('app_settings');
        }

        /** @var User $user */
        $user = $security->getUser();

        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createBand($band, $user, $entityManager, $bandResolver);
            return $this->render('app/_modal_success.html.twig', [
                'redirect' => $this->generateUrl('app_settings'),
            ]);
        }

        return $this->render('app/band/_create_modal.html.twig', [
            'form' => $form,
        ]);
    }

    private function createBand(Band $band, User $user, EntityManagerInterface $entityManager, CurrentBandResolver $bandResolver): void
    {
        $band->setCreatedBy($user);
        $band->setCreatedAt(new \DateTimeImmutable());
        $band->setIsActive(true);

        $member = new BandMember();
        $member->setBand($band);
        $member->setUser($user);
        $member->setJoinedAt(new \DateTimeImmutable());
        $member->setIsActive(true);
        $member->setRoles(['ROLE_BAND_ADMIN']);

        $entityManager->persist($band);
        $entityManager->persist($member);
        $entityManager->flush();

        $bandResolver->switchTo($band, $user);
    }
}
