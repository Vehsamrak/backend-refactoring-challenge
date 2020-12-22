<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\EntityFactoryNotFoundException;

class AbstractEntityFactory
{
    /**
     * @var EntityFactoryInterface[]
     */
    private $factories;

    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param EntityAwareInterface $entityAwareData
     * @return EntityInterface
     * @throws EntityFactoryNotFoundException
     */
    public function create(EntityAwareInterface $entityAwareData): EntityInterface
    {
        $entityClassName = $entityAwareData->getEntityClassName();
        $factory = $this->getFactoryMap()[$entityClassName] ?? null;
        if (null === $factory) {
            throw new EntityFactoryNotFoundException($entityAwareData);
        }

        return $factory->create($entityAwareData);
    }

    /**
     * @return EntityFactoryInterface[]
     */
    private function getFactoryMap(): array
    {
        $factoryMap = [];
        foreach ($this->factories as $factory) {
            $factoryMap[$factory->getEntityClassName()] = $factory;
        }

        return $factoryMap;
    }
}
