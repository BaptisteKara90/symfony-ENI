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
        return $this->render('main/home.html.twig');
    }


    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        $data = json_decode(file_get_contents('https://swapi.dev/api/people/5'));
            dump($data);


            $curl=curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://swapi.dev/api/people/6');
            curl_setopt($curl, CURLOPT_POST);
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['key'=> 'value']);

            curl_exec($curl);
            curl_close($curl);

        return $this->render('main/test.html.twig');
    }
}