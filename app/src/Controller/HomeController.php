<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route('/', name: 'landing')]
    public function index(ReviewRepository $reviewRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $features = [
            [
                'icon' => 'organisez.png',
                'title' => 'Organisez',
                'text' => 'Créez et partagez votre répertoire avec tous les moembres de votre groupe. Soyez toujours prêt à jouer, en toutes circonstances',
            ],
            [
                'icon' => 'repetez.png',
                'title' => 'Répétez',
                'text' => 'Travaillez vos morceaux en toute simplicité graces au backing tracks adaptés à chaque instrument. Répétez ensemble, sur les mêmes versions.',
            ],
            [
                'icon' => 'planifiez.png',
                'title' => 'Planifiez',
                'text' => 'Prévoyez et planifiez vos répétitions, concerts, évènement très simplement grace au planing intégré et au systeme de vote.',
            ],

            [
                'icon' => 'collaborez.png',
                'title' => 'Collaborez',
                'text' => 'Proposez des titres, votez en groupe et décidez ensemble des prochains morceaux à travailler.'
            ],
            [
                'icon' => 'gerez.png',
                'title' => 'Gérez',
                'text'=> 'Suivez les dépenses, les recettes et le budget du groupe en toute transparence.'
            ],
        ];
        $avatars = ['avatar_1.png', 'avatar_2.png', 'avatar_3.png'];
        $testimonials = [];
        foreach ($reviewRepository->findLatest(3) as $i => $review) {
            $user = $userRepository->find($review->getUserId());
            $testimonials[] = [
                'avatar' => $avatars[$i % count($avatars)],
                'name' => $user?->getUsername() ?? 'Utilisateur',
                'text' => $review->getContent(),
            ];
        }

        return $this->render('home/index.html.twig', [
            'features' => $features,
            'testimonials' => $testimonials
        ]);
    }
}
