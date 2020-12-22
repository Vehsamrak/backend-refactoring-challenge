<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityUpdater;

use AppBundle\Dto\UpdateJobRequest;
use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Job;
use AppBundle\Exception\EntityNotFoundException;
use AppBundle\Exception\InvalidEntityException;
use AppBundle\Repository\JobCategoryRepository;
use AppBundle\Repository\JobRepository;
use AppBundle\Repository\ZipcodeRepository;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class JobUpdater implements EntityUpdaterInterface
{
    /** @var EntityManager */
    private $entityManager;

    private $jobRepository;

    private $jobCategoryRepository;

    private $zipcodeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository,
        JobCategoryRepository $jobCategoryRepository,
        ZipcodeRepository $zipcodeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $jobRepository;
        $this->jobCategoryRepository = $jobCategoryRepository;
        $this->zipcodeRepository = $zipcodeRepository;
    }

    public function update($entityId, EntityAwareInterface $entityAware): EntityInterface
    {
        if (!$entityAware instanceof UpdateJobRequest) {
            throw new InvalidEntityException($entityAware);
        }

        $job = $this->jobRepository->findById($entityId);
        if (null === $job) {
            throw new EntityNotFoundException($entityId);
        }

        $job
            ->setTitle($entityAware->getTitle())
            ->setDescription($entityAware->getDescription())
            ->setCategory($this->jobCategoryRepository->findById($entityAware->getCategoryId()))
            ->setDateToBeDone($entityAware->getDateToBeDone())
            ->setZipcode($this->zipcodeRepository->findById($entityAware->getZipcodeId()));

        $this->entityManager->flush($job);

        return $job;
    }

    public function getEntityClassName(): string
    {
        return Job::class;
    }
}
