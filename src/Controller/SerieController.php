<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('series/list.html.twig');
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(): Response
    {
        //TODO renvoyer un formulaire d'ajout de series
        return $this->render('series/add.html.twig');
    }

    #[Route('/{id}', name: 'detail', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detail($id): Response
    {
        //TODO renvoyer les detail d'une série
        return $this->render('series/detail.html.twig');
    }
}
