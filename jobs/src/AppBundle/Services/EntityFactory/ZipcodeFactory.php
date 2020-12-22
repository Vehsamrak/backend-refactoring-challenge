<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

use AppBundle\Dto;
use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Zipcode;
use AppBundle\Exception\InvalidEntityException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ZipcodeFactory implements EntityFactoryInterface
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityClassName(): string
    {
        return Zipcode::class;
    }

    public function create(EntityAwareInterface $entityAware): EntityInterface
    {
        if (!$entityAware instanceof Dto\UpdateZipcodeRequest) {
            throw new InvalidEntityException($entityAware);
        }

        $zipcode = new Zipcode($entityAware->getId(), $entityAware->getCity());

        $this->entityManager->persist($zipcode);
        $this->entityManager->flush($zipcode);

        return $zipcode;
    }
}
