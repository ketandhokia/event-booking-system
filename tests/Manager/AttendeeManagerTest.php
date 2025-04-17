<?php

namespace App\Tests\Manager;

use App\Entity\Attendee;
use App\Manager\AttendeeManager;
use App\Repository\AttendeeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AttendeeManagerTest extends KernelTestCase
{
    private AttendeeManager $attendeeManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $attendeeRepository = $this->createMock(AttendeeRepository::class);

        $passwordHasher->method('hashPassword')
            ->willReturn('password123');
        $attendeeRepository->method('save')
            ->willReturnCallback(function ($attendee) {

                return $attendee;
            });
        $this->attendeeManager = new AttendeeManager(
            $attendeeRepository,
            $passwordHasher
        );
    }

    public function testCreate()
    {
        $params = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
        ];
        $res = $this->attendeeManager->create($params);
        $this->assertInstanceOf(Attendee::class, $res);
        $this->assertEquals($res->getName(), $params['name']);
        $this->assertEquals($res->getEmail(), $params['email']);
        $this->assertEquals($res->getPhone(), $params['phone']);
    }

    public function testUpdate()
    {
        $params = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
        ];
        $res = $this->attendeeManager->update((new Attendee()), $params);
        $this->assertInstanceOf(Attendee::class, $res);
        $this->assertEquals($res->getName(), $params['name']);
        $this->assertEquals($res->getEmail(), $params['email']);
        $this->assertEquals($res->getPhone(), $params['phone']);
    }
}
