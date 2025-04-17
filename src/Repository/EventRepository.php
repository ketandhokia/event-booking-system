<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
}
