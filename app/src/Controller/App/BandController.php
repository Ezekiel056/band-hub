<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Entity\BandMember;
use App\Entity\User;
use App\Form\BandType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BandController extends AbstractController
{
    #[Route('/app/band/create', name: 'app_band_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            $request->getSession()->set('current_band_id', $band->getId());
            $user->setLastBandId($band->getId());
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('app/band/create.html.twig', [
            'form' => $form,
        ]);
    }
}