<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
//        $series = $serieRepository->findBy([], ['name' => 'ASC']);
//        $series = $serieRepository->findByGenresAndPopularity('comedy');


        $maxPage = ceil($serieRepository->count([])/50);
        //s'assurer que la page minimal est 1 et calculer la page max
        if ($page < 1) {
            $page = 1;
           return $this->redirectToRoute("series_list", ['page' => $page]);
        }
        if ($page > $maxPage) {
            $page = $maxPage;
          return $this->redirectToRoute("series_list", ['page' => $maxPage]);
        }

        $series = $serieRepository->findWithPagination($page);

        return $this->render('series/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();
            $this->addFlash('success', 'the TV Show' . $serie->getName() . ' was successfully added!');
            return $this->redirectToRoute('series_detail', ['id' => $serie->getId()]);
        }
        if (!$serieForm->isSubmitted()) {
            $this->addFlash('danger', 'the TV Show' . $serie->getName() . ' was not added!');
            return $this->render('series/add.html.twig', [
                'serieForm' => $serieForm,
            ]);
        }



        return $this->render('series/add.html.twig',[
            'serieForm' => $serieForm,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail($id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        return $this->render('series/detail.html.twig', [
            'serie' => $serie
        ]);
    }
}
