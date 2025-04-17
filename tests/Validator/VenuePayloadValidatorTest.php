<?php

namespace App\Tests\Validator;

use App\Validator\VenuePayloadValidator;
use PHPUnit\Framework\TestCase;

class VenuePayloadValidatorTest extends TestCase
{
    /**
     * @dataProvider venuePayloadProvider
     * @param bool $expectedBool
     * @param array $expected
     * @param array $payload
     * @throws \Exception
     */
    public function testValidate(bool $expectedBool, array $expected, array $payload)
    {
        $validator = new VenuePayloadValidator($payload);

        $this->assertEquals($expectedBool, $validator->validate());
        $this->assertEquals($expected, $validator->errors());
    }

    public function venuePayloadProvider(): array
    {
        $validPayload = [
            'name' => 'John Doe',
            'iso_code' => 'US',
            'capacity' => 100,
        ];
        $missingPayload = [
            'name' => '',
            'iso_code' => '',
            'capacity' => '',
        ];
        $invalidCapacityPayload = [
            'name' => 'John Doe',
            'iso_code' => 'US',
            'capacity' => 'not a number',
        ];

        return [
            'valid payload' => [
                'expectedBool' => true,
                'expected' => [],
                'payload' => $validPayload,
            ],
            'missing payload' => [
                'expectedBool' => false,
                'expected' => [
                    'name' => ['Name is required'],
                    'iso_code' => ['Iso Code is required'],
                    'capacity' => ['Capacity is required', 'Capacity must be numeric'],
                ],
                'payload' => $missingPayload,
            ],
            'invalid capacity payload' => [
                'expectedBool' => false,
                'expected' => [
                    'capacity' => ['Capacity must be numeric'],
                ],
                'payload' => $invalidCapacityPayload,
            ],
        ];

    }
}
