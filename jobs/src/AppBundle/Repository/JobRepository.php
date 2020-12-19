<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Job;
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
}
