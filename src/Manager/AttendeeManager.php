<?php

namespace App\Manager;

use App\Entity\Attendee;
use App\Repository\AttendeeRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AttendeeManager extends BaseManager
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * AttendeeManager constructor.
     *
     * @param AttendeeRepository $attendeeRepository
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(AttendeeRepository $attendeeRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->repository = $attendeeRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param array $params
     * @return Attendee
     */
    public function create(array $params): Attendee
    {
        $attendee = $this->parse(new Attendee(), $params);
        $hashedPassword = $this->passwordHasher->hashPassword($attendee, $params['email']);
        $attendee->setPassword($hashedPassword);
        $attendee->setRoles(['ROLE_ATTENDEE']);

        return $this->save($attendee);
    }

    /**
     * @param Attendee $attendee
     * @param array $params
     * @return Attendee
     */
    public function update(Attendee $attendee, array $params): Attendee
    {
        $attendee = $this->parse($attendee, $params);

        return $this->save($attendee);
    }

    /**
     * @param Attendee $attendee
     * @param array $params
     * @return Attendee
     */
    private function parse(Attendee $attendee, array $params): Attendee
    {
        if (!empty($params['name'])) {
            $attendee->setName($params['name']);
        }
        if (!empty($params['email'])) {
            $attendee->setEmail($params['email']);
        }
        if (!empty($params['phone'])) {
            $attendee->setPhone($params['phone']);
        }

        return $attendee;
    }
}
