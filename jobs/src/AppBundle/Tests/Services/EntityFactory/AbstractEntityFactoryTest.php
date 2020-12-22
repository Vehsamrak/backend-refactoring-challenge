<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services\EntityFactory;

use AppBundle\Entity\EntityInterface;
use AppBundle\Services\EntityFactory\AbstractEntityFactory;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use AppBundle\Services\EntityFactory\EntityFactoryInterface;
use PHPUnit\Framework\TestCase;

class AbstractEntityFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create_GivenFactoryAndEntityAware_MustCallCreateOnFactoryAndReturnEntity(): void
    {
        $entity = $this->createMock(EntityInterface::class);
        $concreteFactory = $this->createConfiguredMock(EntityFactoryInterface::class, ['create' => $entity]);
        $factory = new AbstractEntityFactory([$concreteFactory]);
        /** @var EntityAwareInterface $entityAware */
        $entityAware = $this->createMock(EntityAwareInterface::class);

        $result = $factory->create($entityAware);

        $this->assertSame($entity, $result);
    }
}
