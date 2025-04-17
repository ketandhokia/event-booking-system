<?php

namespace App\Manager;

use App\Entity\Attendee;
use App\Entity\Event;
use App\Entity\EventBooking;
use App\EnumType\BookingStatus;
use App\Repository\EventBookingRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class EventBookingManager extends BaseManager
{
    /**
     * EventBookingManager constructor.
     *
     * @param EventBookingRepository $eventBookingRepository
     */
    public function __construct(EventBookingRepository $eventBookingRepository)
    {
        $this->repository = $eventBookingRepository;
    }

    /**
     * @param Event $event
     * @param Attendee $attendee
     * @return bool
     */
    public function checkIfBookingExists(Event $event, Attendee $attendee): bool
    {
        return $this->repository->checkIfBookingExists($event, $attendee);
    }

    /**
     * @param UserInterface $user
     * @param Event $event
     * @param array $attendees
     * @return array
     */
    public function createBookingForAttendees(UserInterface $user, Event $event, array $attendees): array
    {
        $bookings = [];
        foreach ($attendees as $attendee) {
            $booking = $this->create($user, $event, $attendee);
            $bookings[] = $booking;
        }

        return $bookings;
    }

    /**
     * Create a new event booking.
     *
     * @param UserInterface $user
     * @param Event $event
     * @param Attendee $attendee
     * @return EventBooking
     */
    public function create(UserInterface $user, Event $event, Attendee $attendee): EventBooking
    {
        $booking = (new EventBooking())
            ->setUser($user)
            ->setEvent($event)
            ->setAttendee($attendee)
            ->setStatus(BookingStatus::CONFIRMED)
            ->setBookingTime(new \DateTime())
            ->setCreatedAt(new \DateTimeImmutable());

        return $this->repository->save($booking);
    }
}
