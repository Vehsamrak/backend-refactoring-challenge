<?php

declare(strict_types=1);

namespace AppBundle\Services\EntityFactory;

interface EntityAwareInterface
{
    public function getEntityClassName(): string;
}
