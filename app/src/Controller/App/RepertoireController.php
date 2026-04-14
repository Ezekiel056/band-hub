<?php

namespace App\Controller\App;

use App\Entity\Song;
use App\Enum\SongStatus;
use App\Form\SongType;
use App\Repository\SongRepository;
use App\Service\CurrentBandResolver;
use Doctrine\ORM\EntityManagerInterface;
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

        $counts = []; // Contiendra la liste des status / count
        foreach ($songRepository->countByStatus($this->getCurrentBand()) as $row) {
            $counts[$row['status']->value] = $row['total'];
        }

        /* Recupere le total des chansons */
        $total = $total = array_sum($counts); // plus simple avec array_column

        return $this->render('app/repertoire/repertoire.html.twig', [
            'pageTitle' =>$this->pageTitle,
            'songs' => $songs,
            'filter' => $filter,
            'total' => $total,
            'counts' => $counts,
        ]);
    }


}
