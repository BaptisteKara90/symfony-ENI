<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function home(): Response
    {
        $username = "Batou";
        $serie = ["name" => "Emily in Paris", "year" => 2020];
        return $this->render('main/home.html.twig');
    }



#[Route('/test', name: 'main_test')]
    public function test(): Response
{
    return $this->render('main/test.html.twig');
}
}