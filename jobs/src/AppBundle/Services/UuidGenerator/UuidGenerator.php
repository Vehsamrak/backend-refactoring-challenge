<?php

declare(strict_types=1);

namespace AppBundle\Services\UuidGenerator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Ramsey\Uuid\Uuid;

class UuidGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManager $entityManager
     * @param object|null   $entity
     * @return string
     */
    public function generate(EntityManager $entityManager, $entity): string
    {
        return (string) Uuid::uuid4();
    }
}
