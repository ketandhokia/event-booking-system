<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\AttendeeRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: AttendeeRepository::class)]
class Attendee extends User
{
    #[ORM\Column(length: 255)]
    #[Groups(['attendee'])]
    #[OA\Property(description: 'Name of member', type: 'string', maxLength: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['attendee'])]
    #[OA\Property(description: 'Phone number of member', type: 'string', maxLength: 255)]
    private ?string $phone = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = array_unique(array_merge($this->roles, $roles));

        return $this;
    }
}
