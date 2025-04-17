<?php

namespace App\Tests\Manager;

use App\Entity\Venue;
use App\Manager\VenueManager;
use App\Repository\VenueRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VenueManagerTest extends KernelTestCase
{
    private VenueManager $venueManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $venueRepository = $this->createMock(VenueRepository::class);

        $venueRepository->method('save')
            ->willReturnCallback(function ($venue) {
                return $venue;
            });
        $venueRepository->method('findAll')
            ->willReturn([
                (new Venue())->setName('Venue 1')->setDescription('Description 1')->setIsoCode('US')->setCapacity(100),
                (new Venue())->setName('Venue 2')->setDescription('Description 2')->setIsoCode('CA')->setCapacity(200),
            ]);
        $this->venueManager = new VenueManager(
            $venueRepository
        );
    }

    public function testCreate()
    {
        $params = [
            'name' => 'New Venue',
            'description' => 'A new venue for events',
            'iso_code' => 'US',
            'capacity' => 500,
        ];
        $res = $this->venueManager->create($params);
        $this->assertInstanceOf(Venue::class, $res);
    }

    public function testGetAll()
    {
        $res = $this->venueManager->getAll();
        $this->assertIsArray($res);
        $this->assertCount(2, $res);
    }
}
