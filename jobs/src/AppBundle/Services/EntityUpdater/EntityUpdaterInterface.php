<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityUpdater;

use AppBundle\Entity\EntityInterface;
use AppBundle\Services\EntityFactory\EntityAwareInterface;

interface EntityUpdaterInterface
{
    public function getEntityClassName(): string;

    /**
     * @param string|int           $entityId
     * @param EntityAwareInterface $entityAware
     * @return EntityInterface
     */
    public function update($entityId, EntityAwareInterface $entityAware): EntityInterface;
}
