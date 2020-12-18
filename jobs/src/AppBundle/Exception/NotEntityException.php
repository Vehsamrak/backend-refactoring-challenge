<?php

declare(strict_types=1);

namespace AppBundle\Exception;

use AppBundle\Entity\EntityInterface;
use RuntimeException;
use Throwable;

class NotEntityException extends RuntimeException
{
    public function __construct(string $className, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Class "%s" must implement %s', $className, EntityInterface::class),
            $code,
            $previous
        );
    }
}
