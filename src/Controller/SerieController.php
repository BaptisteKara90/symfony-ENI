<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, int $id = null, SerieRepository $serieRepository): Response
    {

        $serie = $id ?  $serieRepository->find($id) : new Serie();

        if (!$serie){
            throw $this->createNotFoundException('No such TV show !');
        }

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->get('genres')->setData(explode(' / ', $serie->getGenres()));

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {



            $backdrop = $serieForm->get('backdrop')->getData();
            if ($backdrop) {
                /**
                 * @var UploadedFile $backdrop
                 */
                $filename = $serie->getName(). '-' .uniqid() . '.'. $backdrop->guessExtension();
                $backdrop->move("../assets/img/backdrops/", $filename);
                $serie->setBackdrop($filename);
            }


            $serie->setGenres(implode(' / ', $serieForm->get('genres')->getData()));
            $entityManager->persist($serie);
            $entityManager->flush();
            $this->addFlash('success', 'the TV Show' . $serie->getName() . ' was successfully updated!');
            return $this->redirectToRoute('series_detail', ['id' => $serie->getId()]);
        }

        return $this->render('series/save.html.twig',[
            'serieForm' => $serieForm,
            'serieId' => $id
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

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, Serie $serie, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($serie);
        $entityManager->flush();
        $this->addFlash('success', 'the TV Show ' . $serie->getName() . ' was successfully deleted!');
        return $this->redirectToRoute('series_list');

    }
}
