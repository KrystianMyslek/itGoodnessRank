<?php

namespace App\Entity;

use App\Model\GoodnessTypeEnum;
use App\Repository\GoodnessRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

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

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

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

    public function setType(GoodnessTypeEnum $type): static
    {
        $this->type = $type;

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

    // public static function loadValidatorMetadata(ClassMetadata $metadata): void
    // {
    //     $metadata->addPropertyConstraint('icon', new Assert\Image(
    //         minWidth: 200,
    //         maxWidth: 600,
    //         minHeight: 200,
    //         maxHeight: 600,
    //     ));
    // }

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
