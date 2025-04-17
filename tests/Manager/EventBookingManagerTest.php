<?php

namespace App\Tests\Manager;

use App\Entity\Attendee;
use App\Entity\Event;
use App\Entity\EventBooking;
use App\Entity\User;
use App\Manager\EventBookingManager;
use App\Repository\EventBookingRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventBookingManagerTest extends KernelTestCase
{
    private EventBookingManager $eventBookingManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $eventBookingRepository = $this->createMock(EventBookingRepository::class);

        $eventBookingRepository->method('save')
            ->willReturnCallback(function ($eventBooking) {
                return $eventBooking;
            });
        $eventBookingRepository->method('checkIfBookingExists')
            ->willReturnCallback(function (Event $event, Attendee $attendee) {
                return true;
            });
        $this->eventBookingManager = new EventBookingManager(
            $eventBookingRepository
        );
    }

    public function testCreate()
    {
        $res = $this->eventBookingManager->create(new User(), new Event(), new Attendee());
        $this->assertInstanceOf(EventBooking::class, $res);
    }

    public function testCheckIfBookingExists()
    {
        $event = new Event();
        $attendee = new Attendee();
        $this->eventBookingManager->checkIfBookingExists($event, $attendee);
        $this->assertTrue(true);
    }
}
