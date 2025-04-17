<?php

namespace App\Entity;

use App\Repository\VenueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
class Venue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['venue'])]
    #[OA\Property(description: 'The unique identifier of the venue.')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['venue'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank]
    #[Groups(['venue'])]
    #[OA\Property(type: 'string', maxLength: 2)]
    private ?string $isoCode = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['venue'])]
    #[OA\Property(type: 'integer', format: 'int32')]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['venue'])]
    #[OA\Property(type: 'string', format: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsoCode(): ?string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
