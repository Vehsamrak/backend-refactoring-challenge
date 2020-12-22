<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Dto;
use AppBundle\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class JobRepository extends ServiceEntityRepository implements SearchRepositoryInterface
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
     * @param SearchParametersInterface $jobSearchParameters
     * @return Job[]
     * @throws \Exception
     */
    public function findAllByParameters(SearchParametersInterface $jobSearchParameters): array
    {
        if (!$jobSearchParameters instanceof Dto\SearchJobRequest) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Search parameters "%s" must be instance of "%s"',
                    get_class($jobSearchParameters),
                    Dto\SearchJobRequest::class
                )
            );
        }

        $createdFrom = new \DateTime(sprintf('-%d day', $jobSearchParameters->getDaysCount()));

        $queryBuilder = $this->createQueryBuilder('job');
        $queryBuilder
            ->where('job.createdAt >= :createdFrom')
            ->setMaxResults($jobSearchParameters->getLimit())
            ->setFirstResult($jobSearchParameters->getOffset())
            ->setParameter('createdFrom', $createdFrom);

        $categoryId = $jobSearchParameters->getCategoryId();
        if (null !== $categoryId) {
            $queryBuilder
                ->andWhere('job.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $zipcodeId = $jobSearchParameters->getZipcodeId();
        if (null !== $zipcodeId) {
            $queryBuilder
                ->andWhere('job.zipcode = :zipcodeId')
                ->setParameter('zipcodeId', $zipcodeId);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
