<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Entity\User;
use App\Enum\AppMenuTabs;
use App\Form\ChangePasswordType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/settings', name: 'app_settings')]
final class SettingsController extends AppController
{
    #[Route('', name: '', options: ['selected_tab' => AppMenuTabs::Settings], methods: ['GET'])]
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileType::class, $user);
        $passwordForm = $this->createForm(ChangePasswordType::class);

        return $this->render('app/settings/settings.html.twig', [
            'profileForm' => $profileForm,
            'passwordForm' => $passwordForm,
            'pageTitle' => 'Paramètres',
            'bands' => $user->getBandMembers(),
        ]);
    }

    #[Route('/profile', name: '_profile', options: ['selected_tab' => AppMenuTabs::Settings], methods: ['POST'])]
    public function updateProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour.');
        }

        return $this->render('app/settings/settings.html.twig', [
            'profileForm' => $form,
            'passwordForm' => $this->createForm(ChangePasswordType::class),
            'pageTitle' => 'Paramètres',
            'bands' => $user->getBandMembers(),
        ]);
    }

    #[Route('/password', name: '_password', options: ['selected_tab' => AppMenuTabs::Settings], methods: ['POST'])]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current = $form->get('currentPassword')->getData();

            if (!$hasher->isPasswordValid($user, $current)) {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            } else {
                $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));
                $entityManager->flush();
                $this->addFlash('success', 'Mot de passe modifié.');
            }
        }

        return $this->render('app/settings/settings.html.twig', [
            'profileForm' => $this->createForm(ProfileType::class, $user),
            'passwordForm' => $form,
            'pageTitle' => 'Paramètres',
            'bands' => $user->getBandMembers(),
        ]);
    }

    #[Route('/switch/{id}', name: '_switch_band', methods: ['POST'])]
    public function switchBand(Band $band, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $isMember = $user->getBandMembers()->exists(fn($_, $m) => $m->getBand() === $band);

        if ($isMember) {
            $request->getSession()->set('current_band_id', $band->getId());
            $user->setLastBandId($band->getId());
            $entityManager->flush();
            $this->addFlash('success', 'Groupe changé : ' . $band->getName());
        }

        return $this->redirectToRoute('app_home');
    }
}