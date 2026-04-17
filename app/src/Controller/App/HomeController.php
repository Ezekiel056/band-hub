<?php

namespace App\Controller\App;

use App\Enum\AppMenuTabs;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AppController
{

    #[Route('/app', name: 'app_home',options: ['selected_tab' => AppMenuTabs::Home])]
    public function index(): Response
    {
        $pageTitle = "Dashboard";
        return $this->render('app/home/index.html.twig', [
            'pageTitle' => $pageTitle,
        ]);
    }
}
