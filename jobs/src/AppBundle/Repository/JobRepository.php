<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Dto;
use AppBundle\Entity\Job;
use DateTime;
use Exception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class JobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findById(string $id): ?Job
    {
        return $this->find($id);
    }

    /**
     * @param Dto\SearchJobRequest $searchJobRequest
     * @return Job[]
     * @throws Exception
     */
    public function findAllByParameters(Dto\SearchJobRequest $searchJobRequest): array
    {
        $queryBuilder = $this->createQueryBuilder('job');
        $queryBuilder
            ->where('job.createdAt >= :createdFrom')
            ->andWhere('job.category = :categoryId')
            ->andWhere('job.zipcode = :zipcodeId')
            ->setMaxResults($searchJobRequest->getLimit())
            ->setFirstResult($searchJobRequest->getOffset())
            ->setParameters(
                [
                    'createdFrom' => new DateTime(sprintf('-%d day', $searchJobRequest->getDaysCount())),
                    'categoryId' => $searchJobRequest->getCategoryId(),
                    'zipcodeId' => $searchJobRequest->getZipcodeId(),
                ]
            );

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
