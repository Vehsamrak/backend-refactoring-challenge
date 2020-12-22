<?php

declare(strict_types=1);

namespace AppBundle\Exception;

use AppBundle\Services\EntityFactory\EntityAwareInterface;

class EntityUpdaterNotFoundException extends \InvalidArgumentException
{
    public function __construct(EntityAwareInterface $entityAware, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Entity updater not found for %s', $entityAware->getEntityClassName()),
            $code,
            $previous
        );
    }
}
