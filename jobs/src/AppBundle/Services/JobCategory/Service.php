<?php

namespace AppBundle\Services\JobCategory;

use AppBundle\Repository\ServiceRepository;
use AppBundle\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class Service extends AbstractService
{
    /**
     * Service constructor.
     * @param ServiceRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ServiceRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
