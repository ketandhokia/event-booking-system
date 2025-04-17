<?php

namespace App\Manager;

use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class BaseManager.
 */
abstract class BaseManager
{
    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getFind(int $id): mixed
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function getFindAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $parameters
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getFindBy(array$parameters, ?string $orderBy = null): array
    {
        return $this->repository->findBy($parameters, $orderBy);
    }

    /**
     * @param array $parameters
     *
     * @return object|null
     */
    public function getFindOneBy(array $parameters): ?object
    {
        return $this->repository->findOneBy($parameters);
    }

    /**
     * @param $object
     * @return mixed
     */
    public function save($object): mixed
    {
        return $this->repository->save($object);
    }

    /**
     * @param $object
     */
    public function remove($object): void
    {
        $this->repository->remove($object);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->repository->getEntityManager();
    }
}
