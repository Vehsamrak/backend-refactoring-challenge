<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services\UuidGenerator;

use AppBundle\Services\UuidGenerator\UuidGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class UuidGeneratorListener
{
    private $uuidGenerator;

    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $args->getEntityManager();
        $entity = $args->getEntity();

        $metadata = $entityManager->getClassMetadata(get_class($entity));

        // Replace the current generator with a ChainedGenerator
        $metadata->generatorType = ClassMetadata::GENERATOR_TYPE_CUSTOM;
        $metadata->idGenerator = $this->uuidGenerator;
    }
}
