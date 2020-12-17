<?php

declare(strict_types=1);

namespace AppBundle\Services\JobCategory;

use AppBundle\Repository\JobCategoryRepository;
use AppBundle\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class Service extends AbstractService
{
    public function __construct(
        JobCategoryRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
