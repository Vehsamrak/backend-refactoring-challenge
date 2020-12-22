<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

use AppBundle\Dto;
use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Job;
use AppBundle\Exception\InvalidEntityException;
use AppBundle\Repository\JobCategoryRepository;
use AppBundle\Repository\ZipcodeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class JobFactory implements EntityFactoryInterface
{
    /** @var EntityManager */
    private $entityManager;

    private $jobCategoryRepository;

    private $zipcodeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JobCategoryRepository $jobCategoryRepository,
        ZipcodeRepository $zipcodeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->jobCategoryRepository = $jobCategoryRepository;
        $this->zipcodeRepository = $zipcodeRepository;
    }

    public function getEntityClassName(): string
    {
        return Job::class;
    }

    public function create(EntityAwareInterface $entityAware): EntityInterface
    {
        if (!$entityAware instanceof Dto\UpdateJobRequest) {
            throw new InvalidEntityException($entityAware);
        }

        $job = new Job(
            $this->jobCategoryRepository->findById($entityAware->getCategoryId()),
            $this->zipcodeRepository->findById($entityAware->getZipcodeId()),
            $entityAware->getTitle(),
            $entityAware->getDateToBeDone(),
            $entityAware->getDescription()
        );

        $this->entityManager->persist($job);
        $this->entityManager->flush($job);

        return $job;
    }
}
