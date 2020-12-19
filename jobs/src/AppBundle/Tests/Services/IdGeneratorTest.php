<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services;

use AppBundle\Services\IdGenerator\IdGenerator;
use PHPUnit\Framework\TestCase;

class IdGeneratorTest extends TestCase
{
    private const UUID_REGEXP = '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i';

    /**
     * @test
     */
    public function generateUuid_NoParameters_MustReturnValidUuid(): void
    {
        $idGenerator = new IdGenerator();

        $uuid = $idGenerator->generateUuid();

        $this->assertValidUuid($uuid);
    }

    private function assertValidUuid(string $uuid): void
    {
        $this->assertRegExp(self::UUID_REGEXP, $uuid);
    }
}
