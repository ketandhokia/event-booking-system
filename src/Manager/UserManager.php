<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends BaseManager
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserManager constructor.
     *
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->repository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param array $params
     * @return User
     */
    public function create(array $params): User
    {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $params['password']);
        $user
            ->setEmail($params['email'])
            ->setPassword($hashedPassword)
            ->setRoles(['ROLE_USER']);

        return $this->save($user);
    }

}
