<?php

namespace App\Manager;

use App\Entity\Venue;
use App\Repository\VenueRepository;

class VenueManager extends BaseManager
{
    /**
     * VenueManager constructor.
     *
     * @param VenueRepository $venueRepository
     */
    public function __construct(VenueRepository $venueRepository)
    {
        $this->repository = $venueRepository;
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $params
     * @return Venue
     */
    public function create(array $params): Venue
    {
        $venue = (new Venue())
            ->setName($params['name'])
            ->setDescription($params['description'] ?? '')
            ->setIsoCode($params['iso_code'])
            ->setCapacity($params['capacity']);

        return $this->save($venue);
    }

}
