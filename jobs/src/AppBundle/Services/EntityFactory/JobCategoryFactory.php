<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

use AppBundle\Dto;
use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\JobCategory;
use AppBundle\Exception\InvalidEntityException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class JobCategoryFactory implements EntityFactoryInterface
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityClassName(): string
    {
        return JobCategory::class;
    }

    public function create(EntityAwareInterface $entityAware): EntityInterface
    {
        if (!$entityAware instanceof Dto\UpdateJobCategoryRequest) {
            throw new InvalidEntityException($entityAware);
        }

        $jobCategory = new JobCategory($entityAware->getId(), $entityAware->getName());

        $this->entityManager->persist($jobCategory);
        $this->entityManager->flush($jobCategory);

        return $jobCategory;
    }
}
