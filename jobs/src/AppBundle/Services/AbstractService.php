<?php

declare(strict_types=1);

namespace AppBundle\Services;

use AppBundle\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// TODO[petr]: refactor to decouple this class from services
abstract class AbstractService
{
    /**
     * @var ServiceEntityRepositoryInterface
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    // TODO[petr]: move to repository
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param EntityInterface $entity
     * @throws BadRequestHttpException
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface
    {
        if ($this->find($entity->getId())) {
            throw new BadRequestHttpException(sprintf(
                'Resource \'%s\' already exists',
                $entity->getId()
            ));
        }

        return $this->save($entity);
    }
}
