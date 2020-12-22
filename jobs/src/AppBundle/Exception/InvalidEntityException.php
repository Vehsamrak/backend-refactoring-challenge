<?php

declare(strict_types=1);

namespace AppBundle\Exception;

use AppBundle\Services\EntityFactory\EntityAwareInterface;

class InvalidEntityException extends \InvalidArgumentException
{
    public function __construct(EntityAwareInterface $entityAware, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Entity %s is not applicable', $entityAware->getEntityClassName()),
            $code,
            $previous
        );
    }
}
