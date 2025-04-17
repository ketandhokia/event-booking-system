<?php

namespace App\Repository;

use App\Entity\Attendee;
use Doctrine\Persistence\ManagerRegistry;

class AttendeeRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendee::class);
    }
}
