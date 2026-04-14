<?php

namespace App\Controller\App;

use App\Enum\SongStatus;
use App\Repository\SongRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RepertoireController extends AppController
{

    private $pageTitle = 'Repertoire';

    #[Route('app/repertoire', name: 'app_repertoire', methods: ['GET'])]
    public function index(
        SongRepository $songRepository,
        Request $request,
    ): Response {
        $status  = SongStatus::tryFrom($request->query->get('status'));
        $songs = $songRepository->findByBand($this->getCurrentBand(),$status);

        $counts = [];
        $total = 0;
        foreach ($songRepository->countByStatus($this->getCurrentBand()) as $row) {
            $counts[$row['status']->value] = $row['total'];
        }

        if ($counts) {
            $total = $total = array_sum($counts);
        }

        return $this->render('app/repertoire/repertoire.html.twig', [
            'pageTitle' =>$this->pageTitle,
            'songs' => $songs,
            'filterStatus' => $status,
            'total' => $total,
            'counts' => $counts,
        ]);
    }






}
