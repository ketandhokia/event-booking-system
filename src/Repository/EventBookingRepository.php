<?php

namespace App\Repository;

use App\Entity\Attendee;
use App\Entity\Event;
use App\Entity\EventBooking;
use Doctrine\Persistence\ManagerRegistry;

class EventBookingRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventBooking::class);
    }

    /**
     * Check if a booking exists for a given attendee and event.
     *
     * @param Event $event
     * @param Attendee $attendee
     * @return bool
     */
    public function checkIfBookingExists(Event $event, Attendee $attendee): bool
    {
        $qb = $this->createQueryBuilder('eb')
            ->andWhere('eb.event = :event')
            ->andWhere('eb.attendee = :attendee')
            ->setParameter('event', $event)
            ->setParameter('attendee', $attendee);

        return (bool) $qb->getQuery()->getOneOrNullResult();
    }
}
