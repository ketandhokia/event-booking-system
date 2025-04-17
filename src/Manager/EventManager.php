<?php

namespace App\Manager;

use App\Entity\Event;
use App\Entity\Venue;
use App\Repository\EventRepository;
use DateMalformedStringException;

class EventManager extends BaseManager
{
    /**
     * EventManager constructor.
     *
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->repository = $eventRepository;
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param Venue $venue
     * @param array $params
     * @return Event
     * @throws DateMalformedStringException
     */
    public function create(Venue $venue, array $params): Event
    {
        $event = new Event();
        $event->setVenue($venue);
        $event = $this->parse($event, $params);

        return $this->save($event);
    }

    /**
     * @param Event $event
     * @param array $params
     * @return Event
     * @throws DateMalformedStringException
     */
    public function update(Event $event, array $params): Event
    {
        $event = $this->parse($event, $params);

        return $this->save($event);
    }

    /**
     * @param Event $event
     * @param int $numOfTickets
     * @return Event
     */
    public function updateAvailableSeats(Event $event, int $numOfTickets): Event
    {
        $event->setAvailableSeats($event->getAvailableSeats() - $numOfTickets);

        return $this->save($event);
    }

    /**
     * @param $event
     * @param array $params
     * @return mixed
     * @throws DateMalformedStringException
     */
    private function parse($event, array $params): Event
    {
        if (!empty($params['title'])) {
            $event->setTitle($params['title']);
        }
        if (!empty($params['description'])) {
            $event->setDescription($params['description']);
        }
        if (!empty($params['start_time'])) {
            $event->setStartTime(new \DateTime($params['start_time']));
        }
        if (!empty($params['end_time'])) {
            $event->setEndTime(new \DateTime($params['end_time']));
        }
        if (!empty($params['total_seats'])) {
            $event->setTotalSeats($params['total_seats']);
        }
        if (!empty($params['price'])) {
            $event->setPrice($params['price']);
        }
        if (!$event->getAvailableSeats()) {
            $event->setAvailableSeats($params['total_seats']);
        }
        if (!$event->getCreatedAt()) {
            $event->setCreatedAt(new \DateTimeImmutable());
        }

        return $event;
    }
}
