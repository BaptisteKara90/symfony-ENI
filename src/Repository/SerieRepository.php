<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findByGenresAndPopularity(string $genre){
        //en DQL
//        $dql = "SELECT s FROM App\Entity\Serie AS s WHERE s.genres LIKE :genre ORDER BY s.popularity DESC";
//        $query = $this->getEntityManager()->createQuery($dql);
//        $query->setParameter('genre', "%$genre%");
//        return $query->getResult();

        //en queryBuilder

        $qb = $this->createQueryBuilder('s');
        $qb->andWhere("s.genres LIKE :genre")
            ->setParameter('genre', '%'.$genre.'%')
            ->addOrderBy('s.popularity', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findWithPagination(int $page, int $limit = 50){
        $qb = $this->createQueryBuilder('s');

        $qb->addOrderBy('s.popularity', 'DESC');
        $offset = ($page - 1) * $limit;
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
