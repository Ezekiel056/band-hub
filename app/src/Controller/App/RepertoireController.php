<?php

namespace App\Controller\App;

use App\Repository\SongRepository;
use App\Service\CurrentBandResolver;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RepertoireController extends AppController
{
    private $pageTitle = 'Repertoire';

    #[Route('app/repertoire', name: 'app_repertoire')]
    public function index(
        SongRepository $songRepository,
        CurrentBandResolver $currentBandResolver
    ): Response {

        $songs = $songRepository->findByBand($this->getCurrentBand());

        return $this->render('app/repertoire/index.html.twig', [
            'pageTitle' =>$this->pageTitle,
            'songs' => $songs,
        ]);
    }
}
