<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event'])]
    #[OA\Property(description: 'The unique identifier of the event.')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Venue::class)]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id')]
    #[Groups(['event'])]
    #[OA\Property(description: 'The venue where the event takes place.')]
    private Venue|null $venue = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['event'])]
    #[OA\Property(type: 'string', format: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event'])]
    #[OA\Property(type: 'datetime', format: 'date-time')]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event'])]
    #[OA\Property(type: 'datetime', format: 'date-time')]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    #[Groups(['event'])]
    #[OA\Property(type: 'integer', format: 'int32')]
    private ?int $totalSeats = null;

    #[ORM\Column]
    #[Groups(['event'])]
    #[OA\Property(type: 'integer', format: 'int32')]
    private ?int $availableSeats = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['event'])]
    #[OA\Property(type: 'string', format: 'decimal')]
    private ?string $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    public function setVenue(?Venue $venue = null): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTotalSeats(): ?int
    {
        return $this->totalSeats;
    }

    public function setTotalSeats(int $totalSeats): self
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): self
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
