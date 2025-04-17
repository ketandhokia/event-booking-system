<?php

namespace App\Tests\Manager;

use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManagerTest extends KernelTestCase
{
    private UserManager $userManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userRepository = $this->createMock(UserRepository::class);

        $passwordHasher->method('hashPassword')
            ->willReturn('password123');
        $userRepository->method('save')
            ->willReturnCallback(function ($user) {

                return $user;
            });
        $this->userManager = new UserManager(
            $userRepository,
            $passwordHasher
        );
    }

    public function testCreate()
    {
        $params = [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ];
        $res = $this->userManager->create($params);
        $this->assertInstanceOf(User::class, $res);
    }
}
