<?php

namespace App\Service;

use App\Entity\Goodness;
use App\Entity\User;
use App\Entity\Vote;
use App\Model\VoteScoringEnum;
use Doctrine\ORM\EntityManagerInterface;

class VoteService
{
	public function __construct(
		private EntityManagerInterface $manager,
	) {
	}

	public function create(Goodness $goodness, User $user, VoteScoringEnum $scoring): void
	{
		$vote = new Vote();
		$vote->setGoodness($goodness);
		$vote->setUser($user);
		$vote->setScoring($scoring);
		$vote->setCreatedAt(new \DateTimeImmutable('now'));

		$this->manager->persist($vote);
		$this->manager->flush();
	}

	public function update(Vote $vote, VoteScoringEnum $scoring): void
	{
		$vote->setScoring($scoring);

		$this->manager->flush();
	}
}