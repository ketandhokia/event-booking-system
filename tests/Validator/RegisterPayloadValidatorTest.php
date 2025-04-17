<?php

namespace App\Tests\Validator;

use App\Validator\RegisterPayloadValidator;
use PHPUnit\Framework\TestCase;

class RegisterPayloadValidatorTest extends TestCase
{
    /**
     * @dataProvider registerPayloadProvider
     * @param bool $expectedBool
     * @param array $expected
     * @param array $payload
     * @throws \Exception
     */
    public function testValidate(bool $expectedBool, array $expected, array $payload)
    {
        $validator = new RegisterPayloadValidator($payload);

        $this->assertEquals($expectedBool, $validator->validate());
        $this->assertEquals($expected, $validator->errors());
    }

    public function registerPayloadProvider(): array
    {
        $validPayload = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        $missingPayload = [
            'email' => '',
            'password' => '',
        ];
        $invalidEmailPayload = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        return [
            'valid payload' => [true, [], $validPayload],
            'missing payload' => [false, ['email' => ['Email is required', 'Email is not a valid email address'], 'password' => ['Password is required']], $missingPayload],
            'invalid email payload' => [false, ['email' => ['Email is not a valid email address']], $invalidEmailPayload],
        ];
    }
}
