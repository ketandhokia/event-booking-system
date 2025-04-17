<?php

namespace App\Tests\Manager;

use App\Entity\Event;
use App\Entity\Venue;
use App\Manager\EventManager;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventManagerTest extends KernelTestCase
{
    private EventManager $eventManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $eventRepository = $this->createMock(EventRepository::class);

        $eventRepository->method('save')
            ->willReturnCallback(function ($venue) {
                return $venue;
            });
        $eventRepository->method('findAll')
            ->willReturn([

            ]);
        $this->eventManager = new EventManager(
            $eventRepository
        );
    }

    public function testCreate()
    {
        $params = [
            'venue_id' => 1,
            'title' => 'New Event',
            'description' => 'Event description',
            'start_time' => '2023-10-01 10:00:00',
            'end_time' => '2023-10-01 12:00:00',
            'total_seats' => 100,
            'price' => 50.00,
        ];
        $res = $this->eventManager->create((new Venue()), $params);
        $this->assertInstanceOf(Event::class, $res);
        $this->assertEquals($res->getTitle(), $params['title']);
        $this->assertEquals($res->getDescription(), $params['description']);
        $this->assertEquals($res->getStartTime()->format('Y-m-d H:i:s'), $params['start_time']);
        $this->assertEquals($res->getEndTime()->format('Y-m-d H:i:s'), $params['end_time']);
        $this->assertEquals($res->getTotalSeats(), $params['total_seats']);
        $this->assertEquals($res->getAvailableSeats(), $params['total_seats']);
        $this->assertEquals($res->getPrice(), $params['price']);
    }

    public function testUpdate()
    {
        $params = [
            'venue_id' => 1,
            'title' => 'Updated Event',
            'description' => 'Updated description',
            'start_time' => '2023-10-01 10:00:00',
            'end_time' => '2023-10-01 12:00:00',
            'total_seats' => 100,
            'price' => 50.00,
        ];

        $res = $this->eventManager->update((new Event()), $params);
        $this->assertInstanceOf(Event::class, $res);
        $this->assertEquals($res->getTitle(), $params['title']);
        $this->assertEquals($res->getDescription(), $params['description']);
        $this->assertEquals($res->getStartTime()->format('Y-m-d H:i:s'), $params['start_time']);
        $this->assertEquals($res->getEndTime()->format('Y-m-d H:i:s'), $params['end_time']);
        $this->assertEquals($res->getTotalSeats(), $params['total_seats']);
        $this->assertEquals($res->getAvailableSeats(), $params['total_seats']);
        $this->assertEquals($res->getPrice(), $params['price']);
    }

    public function testUpdateAvailableSeats()
    {
        $event = (new Event())
            ->setVenue(new Venue())
            ->setAvailableSeats(100);

        $res = $this->eventManager->updateAvailableSeats($event, 10);
        $this->assertInstanceOf(Event::class, $res);
        $this->assertEquals($event->getAvailableSeats(), 90);
    }
}
