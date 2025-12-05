<?php

namespace App\Entity;

use App\Model\GoodnessTypeEnum;
use App\Model\GoodnessStatusEnum;
use App\Repository\GoodnessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoodnessRepository::class)]
class Goodness
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private GoodnessTypeEnum $type;
    
    #[ORM\Column]
    private GoodnessStatusEnum $status;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Vote>
     */
    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'goodness')]
    private Collection $votes;

    #[ORM\Column]

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): GoodnessTypeEnum
    {
        return $this->type;
    }

    public function getTypeLabel(): string
    {
        return $this->type->getLabel();
    }

    public function getTypeIcon(): string
    {
        return $this->type->getIcon();
    }

    public function setType(GoodnessTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): GoodnessStatusEnum
    {
        return $this->status;
    }

    public function setStatus(GoodnessStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

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

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setGoodness($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getGoodness() === $this) {
                $vote->setGoodness(null);
            }
        }

        return $this;
    }

}
