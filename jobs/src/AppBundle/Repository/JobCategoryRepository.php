<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\JobCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class JobCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobCategory::class);
    }

    /**
     * @return JobCategory[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findById(int $id): ?JobCategory
    {
        return $this->find($id);
    }
}
