<?php

namespace App\Repository;

use App\Entity\Goodness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Goodness>
 */
class GoodnessRepository extends ServiceEntityRepository
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Goodness::class);
    }

   /**
    * @return Goodness[] Returns an array of Goodness objects
    */
   public function findPodium(): array
   {
        return $this->createQueryBuilder('g')
            ->join('g.votes', 'v')
            ->addGroupBy('v.goodness')
            ->orderBy('SUM(v.scoring)', 'DESC')
            ->getQuery()
            ->setMaxResults(3)
            ->getResult();

   }

}
