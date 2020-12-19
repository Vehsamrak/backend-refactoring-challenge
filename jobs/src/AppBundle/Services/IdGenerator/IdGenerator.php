<?php

declare(strict_types=1);

namespace AppBundle\Services\IdGenerator;

use Ramsey\Uuid\Uuid;

class IdGenerator implements IdGeneratorInterface
{
    public function generateUuid(): string
    {
        return (string) Uuid::uuid4();
    }
}
