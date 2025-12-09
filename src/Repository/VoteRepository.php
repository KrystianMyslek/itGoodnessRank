<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    /**
     * @return Vote[] Returns an array of Vote objects
    */
    public function findScoringAndBindByGoodnessId(): array
    {
        $votes = [];
        foreach ($this->findAll() as $vote) {
            $votes[$vote->getGoodness()->getId()] = $vote->getScoring();
        }

       return $votes;
   }

    /**
     * @return Vote[] Returns an array of Vote objects
    */
    public function findScoringByUserAndBindByGoodnessId(User $user): array
    {
        $votes = [];
        foreach ($this->findBy(['user' => $user->getId()]) as $vote) {
            $votes[$vote->getGoodness()->getId()] = $vote->getScoring();
        }

       return $votes;
   }

}
