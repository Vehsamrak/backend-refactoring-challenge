<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityUpdater;

use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\EntityUpdaterNotFoundException;
use AppBundle\Services\EntityFactory\EntityAwareInterface;

class AbstractEntityUpdater
{
    /**
     * @var EntityUpdaterInterface[]
     */
    private $updaters;

    public function __construct(iterable $updaters)
    {
        $this->updaters = $updaters;
    }

    /**
     * @param string|int           $entityId
     * @param EntityAwareInterface $entityAwareData
     * @return EntityInterface
     */
    public function update($entityId, EntityAwareInterface $entityAwareData): EntityInterface
    {
        $entityClassName = $entityAwareData->getEntityClassName();
        /** @var EntityUpdaterInterface $updater */
        $updater = $this->getUpdaterMap()[$entityClassName] ?? null;
        if (null === $updater) {
            throw new EntityUpdaterNotFoundException($entityAwareData);
        }

        return $updater->update($entityId, $entityAwareData);
    }

    /**
     * @return EntityUpdaterInterface[]
     */
    private function getUpdaterMap(): array
    {
        $factoryMap = [];
        foreach ($this->updaters as $updater) {
            $factoryMap[$updater->getEntityClassName()] = $updater;
        }

        return $factoryMap;
    }
}
