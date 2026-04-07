<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route('/', name: 'landing')]
    public function index(): Response
    {

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
        $testimonials = [
            [
                'avatar'=>'avatar_1.png',
                'name'=>'Julian K.',
                'text'=>'Cette application est juste géniale! Elle nous ...'
            ],
            [
                'avatar'=>'avatar_2.png',
                'name'=>'Sarah M.',
                'text'=>'C\'est tellement devenu plus simple de s\'organiser ...'
            ],
            [
                'avatar'=>'avatar_3.png',
                'name'=>'Marc M.',
                'text'=>'Fini les prises de tête pour planifier les répétitions ...'
            ]
        ];

        return $this->render('home/index.html.twig', [
            'features' => $features,
            'testimonials' => $testimonials
        ]);
    }
}
