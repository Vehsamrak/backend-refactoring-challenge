<?php

declare(strict_types=1);

namespace AppBundle\Services\Job;

use AppBundle\Entity\EntityInterface;
use AppBundle\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Job as JobEntity;

class Job
{
    private $jobRepository;

    private $entityManager;

    public function __construct(JobRepository $jobRepository, EntityManagerInterface $entityManager)
    {
        $this->jobRepository = $jobRepository;
        $this->entityManager = $entityManager;
    }

    public function create(EntityInterface $entity): EntityInterface
    {
        return $this->save($entity);
    }

    /**
     * @param EntityInterface $entity
     * @return JobEntity
     */
    public function update(EntityInterface $entity): JobEntity
    {
        $persistedEntity = $this->jobRepository->findById($entity->getId());
        if (is_null($persistedEntity)) {
            throw new NotFoundHttpException(
                sprintf(
                    'The resource \'%s\' was not found.',
                    $entity->getId()
                )
            );
        }

        return $this->save($entity);
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    protected function save(EntityInterface $entity): EntityInterface
    {
        if (is_null($entity->getId())) {
            $this->entityManager->persist($entity);
        } else {
            $this->entityManager->merge($entity);
        }

        $this->entityManager->flush();

        return $entity;
    }
}
