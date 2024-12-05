<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/series', name: 'api_serie_')]
class SerieController extends AbstractController
{

    public function __construct(private SerieRepository $repository)
    {
    }

    #[Route('', name: 'all', methods: ['GET'])]
    public function all(): Response
    {
        $series = $this->repository->findAll();
        return $this->json($series, Response::HTTP_OK, [],['groups' => 'serie-api']);
    }

    #[Route('/{id}', name: 'one', methods: ['GET'])]
    public function one(int $id): Response
    {
        $serie = $this->repository->find($id);
        return $this->json($serie, Response::HTTP_OK, [],['groups' => 'serie-api']);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {

        $data = $request->getContent();
        $serie = $serializer->deserialize($data, Serie::class, 'json');

        //pour valider avec les annotations de l'entitée (voir formtype)
//        $test = $validator->validate($serie);

        $entityManager->persist($serie);
        $entityManager->flush();
        return $this->json($serie, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Serie $serie, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $data = $request->getContent();
        $serie = $serializer->deserialize($data, Serie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $serie]);
        $entityManager->persist($serie);
        $entityManager->flush();
        return $this->json($serie, Response::HTTP_ACCEPTED, [],['groups' => 'serie-api']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->json(["delete" => "Success"], Response::HTTP_OK);
    }
}
