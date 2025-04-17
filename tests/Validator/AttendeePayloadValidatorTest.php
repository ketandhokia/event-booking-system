<?php

namespace App\Tests\Validator;

use App\Validator\AttendeePayloadValidator;
use PHPUnit\Framework\TestCase;

class AttendeePayloadValidatorTest extends TestCase
{
    /**
     * @dataProvider attendeePayloadProvider
     * @param bool $expectedBool
     * @param array $expected
     * @param array $payload
     * @throws \Exception
     */
    public function testValidate(bool $expectedBool, array $expected, array $payload)
    {
        $validator = new AttendeePayloadValidator($payload);

        $this->assertEquals($expectedBool, $validator->validate());
        $this->assertEquals($expected, $validator->errors());
    }

    public function attendeePayloadProvider(): array
    {
        $validPayload = [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
        ];

        $invalidPayload = [
            'name' => '',
            'email' => 'invalid-email',
            'phone' => '',
        ];

        return [
            'valid payload' => [
                'expectedBool' => true,
                'expected' => [],
                'payload' => $validPayload,
            ],
            'invalid payload' => [
                'expectedBool' => false,
                'expected' => [
                    'name' => ['Name is required'],
                    'email' => ['Email is not a valid email address'],
                    'phone' => ['Phone is required'],
                ],
                'payload' => $invalidPayload,
            ],
        ];
    }
}
