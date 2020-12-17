<?php

declare(strict_types=1);

namespace AppBundle\Services\Zipcode;

use AppBundle\Repository\ZipcodeRepository;
use AppBundle\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class Zipcode extends AbstractService
{
    /**
     * Service constructor.
     * @param ZipcodeRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ZipcodeRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
