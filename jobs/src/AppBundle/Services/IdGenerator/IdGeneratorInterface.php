<?php

declare(strict_types=1);

namespace AppBundle\Services\IdGenerator;

interface IdGeneratorInterface
{
    public function generateUuid(): string;
}
