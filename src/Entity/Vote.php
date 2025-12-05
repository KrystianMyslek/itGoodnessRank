<?php

namespace App\Entity;

use App\Model\VoteScoringEnum;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Goodness $goodness = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?User $user = null;

    #[ORM\Column]
    private VoteScoringEnum $scoring;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoodness(): ?Goodness
    {
        return $this->goodness;
    }

    public function setGoodness(?Goodness $goodness): static
    {
        $this->goodness = $goodness;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getScoring(): VoteScoringEnum
    {
        return $this->scoring;
    }

    public function setScoring(VoteScoringEnum $scoring): static
    {
        $this->scoring = $scoring;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
