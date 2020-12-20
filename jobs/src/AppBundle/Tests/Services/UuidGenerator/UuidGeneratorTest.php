<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services\UuidGenerator;

use AppBundle\Services\UuidGenerator\UuidGenerator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class UuidGeneratorTest extends TestCase
{
    private const UUID_REGEXP = '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i';

    /**
     * @test
     */
    public function generate_NoParameters_MustReturnValidUuid(): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->createMock(EntityManager::class);
        $idGenerator = new UuidGenerator();

        $uuid = $idGenerator->generate($entityManager, null);

        $this->assertValidUuid($uuid);
    }

    private function assertValidUuid(string $uuid): void
    {
        $this->assertRegExp(self::UUID_REGEXP, $uuid);
    }
}
