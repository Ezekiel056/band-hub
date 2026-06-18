<?php

namespace App\Controller\App;

use App\Enum\AppMenuTabs;
use App\Enum\SongStatus;
use App\Repository\SetlistModelRepository;
use App\Repository\SongRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AppController
{
    #[Route('/app', name: 'app_home', options: ['selected_tab' => AppMenuTabs::Home])]
    public function index(SongRepository $songRepository, SetlistModelRepository $setlistRepository): Response
    {
        $band = $this->getCurrentBand();

        $songs = $songRepository->findByBand($band);
        $totalDuration = array_sum(array_map(fn($s) => $s->getDuration(), $songs));

        $countByStatus = [];
        foreach ($songRepository->countByStatus($band, false) as $row) {
            $countByStatus[$row['status']->value] = $row['total'];
        }

        return $this->render('app/home/index.html.twig', [
            'pageTitle'      => 'Dashboard',
            'totalSongs'     => count($songs),
            'totalSetlists'  => count($setlistRepository->findByBand($band)),
            'totalDuration'  => $totalDuration,
            'countByStatus'  => $countByStatus,
            'learningSongs'  => $songRepository->findByBand($band, SongStatus::Learning),
            'recentSetlists' => $setlistRepository->findRecentByBand($band),
        ]);
    }
}