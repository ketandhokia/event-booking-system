<?php

namespace App\Entity;

use App\EnumType\BookingStatus;
use App\Repository\EventBookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventBookingRepository::class)]
class EventBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['booking'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[Groups(['booking'])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    #[Groups(['booking'])]
    private ?Event $event = null;

    #[ORM\ManyToOne(targetEntity: Attendee::class)]
    #[ORM\JoinColumn(name: 'attendee_id', referencedColumnName: 'id')]
    #[Groups(['booking'])]
    private ?Attendee $attendee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['booking'])]
    private ?\DateTimeInterface $bookingTime = null;

    #[ORM\Column(enumType: BookingStatus::class)]
    #[Groups(['booking'])]
    private ?BookingStatus $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getAttendee(): ?Attendee
    {
        return $this->attendee;
    }

    public function setAttendee(?Attendee $attendee): self
    {
        $this->attendee = $attendee;

        return $this;
    }

    public function getBookingTime(): ?\DateTimeInterface
    {
        return $this->bookingTime;
    }

    public function setBookingTime(\DateTimeInterface $bookingTime): self
    {
        $this->bookingTime = $bookingTime;

        return $this;
    }

    public function getStatus(): ?BookingStatus
    {
        return $this->status;
    }

    public function setStatus(BookingStatus $status): self
    {
        $this->status = $status;

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
