<?php

namespace App\Tests\Validator;

use App\Validator\EventPayloadValidator;
use PHPUnit\Framework\TestCase;

class EventPayloadValidatorTest extends TestCase
{
    /**
     * @dataProvider eventPayloadProvider
     * @param bool $expectedBool
     * @param array $expected
     * @param array $payload
     * @throws \Exception
     */
    public function testValidate(bool $expectedBool, array $expected, array $payload)
    {
        $validator = new EventPayloadValidator($payload);

        $this->assertEquals($expectedBool, $validator->validate());
        $this->assertEquals($expected, $validator->errors());
    }

    public function eventPayloadProvider(): array
    {
        $validNewPayload = [
            'action' => 'new',
            'venue_id' => 1,
            'title' => 'New Event',
            'description' => 'Event description',
            'start_time' => '2023-10-01 10:00:00',
            'end_time' => '2023-10-01 12:00:00',
            'total_seats' => 100,
            'price' => 50.00,
        ];
        $missingNewPayload = [
            'action' => 'new',
            'venue_id' => null,
            'title' => '',
            'description' => '',
            'start_time' => '',
            'end_time' => '',
            'total_seats' => null,
            'price' => null,
        ];

        $validUpdatePayload = [
            'action' => 'update',
            'title' => 'Updated Event',
            'description' => 'Updated description',
            'start_time' => '2023-10-01 10:00:00',
            'end_time' => '2023-10-01 12:00:00',
            'total_seats' => 150,
            'price' => 60.00,
        ];

        return [
            'valid new payload' => [
                true,
                [],
                $validNewPayload,
            ],
            'missing new payload' => [
                false,
                [
                    'venue_id' => ['Venue Id is required', 'Venue Id must be numeric'],
                    'title' => ['Title is required'],
                    'description' => ['Description is required'],
                    'start_time' => ['Start Time is required', 'Start Time is not a valid date'],
                    'end_time' => ['End Time is required', 'End Time is not a valid date'],
                    'total_seats' => ['Total Seats is required', 'Total Seats must be numeric'],
                    'price' => ['Price is required', 'Price must be numeric'],
                ],
                $missingNewPayload,
            ],
            'valid update payload' => [
                true,
                [],
                $validUpdatePayload,
            ],
        ];
    }
}
