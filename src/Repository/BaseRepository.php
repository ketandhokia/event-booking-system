<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * BaseRepository constructor.
     *
     * @param ManagerRegistry $managerRegistry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $managerRegistry, string $entityClass)
    {
        parent::__construct($managerRegistry, $entityClass);
    }

    /**
     * @param $object
     *
     * @return mixed
     */
    public function save($object): mixed
    {
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();

        return $object;
    }

    /**
     * @param $object
     */
    public function remove($object): void
    {
        $this->getEntityManager()->remove($object);
        $this->getEntityManager()->flush();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return parent::getEntityManager();
    }
}
