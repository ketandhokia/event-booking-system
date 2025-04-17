<?php

namespace App\Tests\Validator;

use App\Validator\EventBookingPayloadValidator;
use PHPUnit\Framework\TestCase;

class EventBookingPayloadValidatorTest extends TestCase
{
    /**
     * @dataProvider eventBookingPayloadProvider
     * @param bool $expectedBool
     * @param array $expected
     * @param array $payload
     * @throws \Exception
     */
    public function testValidate(bool $expectedBool, array $expected, array $payload)
    {
        $validator = new EventBookingPayloadValidator($payload);

        $this->assertEquals($expectedBool, $validator->validate());
        $this->assertEquals($expected, $validator->errors());
    }

    public function eventBookingPayloadProvider(): array
    {
        $payload = [
            'event_id' => 1,
            'attendee_ids' => [1, 2, 3],
        ];

        return [
            'valid payload' => [
                'expectedBool' => true,
                'expected' => [],
                'payload' => $payload,
            ],
            'missing event_id' => [
                'expectedBool' => false,
                'expected' => ['event_id' => ['Event Id is required', 'Event Id must be numeric']],
                'payload' => array_merge($payload, ['event_id' => null]),
            ],
            'missing attendee_ids' => [
                'expectedBool' => false,
                'expected' => ['attendee_ids' => ['Attendee Ids is required', 'Attendee Ids Invalid']],
                'payload' => array_merge($payload, ['attendee_ids' => null]),
            ],
            'invalid event_id type' => [
                'expectedBool' => false,
                'expected' => ['event_id' => ['Event Id must be numeric']],
                'payload' => array_merge($payload, ['event_id' => 'string']),
            ],
            'invalid attendee_ids type' => [
                'expectedBool' => false,
                'expected' => ['attendee_ids' => ['Attendee Ids Invalid']],
                'payload' => array_merge($payload, ['attendee_ids' => 1]),
            ],
        ];
    }
}
