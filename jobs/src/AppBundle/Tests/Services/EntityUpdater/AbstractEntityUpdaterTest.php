<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services\EntityUpdater;

use AppBundle\Entity\EntityInterface;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use AppBundle\Services\EntityUpdater\AbstractEntityUpdater;
use AppBundle\Services\EntityUpdater\EntityUpdaterInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class AbstractEntityUpdaterTest extends TestCase
{
    private const ENTITY_ID = 1;

    /**
     * @test
     */
    public function update_GivenUpdaterAndEntityAware_MustCallUpdateOnUpdaterAndReturnEntity(): void
    {
        $entity = $this->createMock(EntityInterface::class);
        $concreteUpdater = $this->createConfiguredMock(EntityUpdaterInterface::class, ['update' => $entity]);
        $updater = new AbstractEntityUpdater([$concreteUpdater]);
        /** @var EntityAwareInterface $entityAware */
        $entityAware = $this->createMock(EntityAwareInterface::class);

        $result = $updater->update(self::ENTITY_ID, $entityAware);

        $this->assertSame($entity, $result);
    }
}


