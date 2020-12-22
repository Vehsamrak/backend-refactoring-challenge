<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

use AppBundle\Entity\EntityInterface;

interface EntityFactoryInterface
{
    public function create(EntityAwareInterface $entityData): EntityInterface;

    public function getEntityClassName(): string;
}
