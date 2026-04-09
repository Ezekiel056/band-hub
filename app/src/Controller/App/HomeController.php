<?php

namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AppController
{

    #[Route('/app', name: 'app_home')]
    public function index(): Response
    {
        $pageTitle = "Dashboard";
        return $this->render('app/home/index.html.twig', [
            'pageTitle' => $pageTitle,
        ]);
    }
}
