<?php

namespace App\Controller\App;

use App\Enum\SongStatus;
use App\Repository\SongRepository;
use App\Service\CurrentBandResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RepertoireController extends AppController
{
    private $pageTitle = 'Repertoire';

    #[Route('app/repertoire', name: 'app_repertoire', methods: ['GET'])]
    public function index(
        SongRepository $songRepository,
        CurrentBandResolver $currentBandResolver,
        Request $request,
    ): Response {

        $filter  = SongStatus::tryFrom(array_key_first($request->query->all()));
        $songs = $songRepository->findByBand($this->getCurrentBand(),$filter ?? null);

        return $this->render('app/repertoire/repertoire.html.twig', [
            'pageTitle' =>$this->pageTitle,
            'songs' => $songs,
            'filter' => $filter,
        ]);
    }
}
