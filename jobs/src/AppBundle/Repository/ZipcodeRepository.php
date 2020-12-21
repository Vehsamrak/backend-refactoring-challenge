<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Zipcode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ZipcodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Zipcode::class);
    }

    public function findById(string $id): ?Zipcode
    {
        return $this->find($id);
    }
}
