<?php

namespace App\Controller\App;

use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RepertoireController extends AbstractController
{
    private $pageTitle = 'Repertoire';

    #[Route('app/repertoire', name: 'app_repertoire')]
    public function index(SongRepository $repo): Response
    {

        $songs = $repo->findAll();

        return $this->render('app/repertoire/index.html.twig', [
            'pageTitle' =>$this->pageTitle,
            'songs' => $songs,
        ]);
    }
}
