<?php

declare(strict_types=1);

namespace AppBundle\Exception;

use RuntimeException;
use Throwable;

class ClassNotFoundException extends RuntimeException
{
    public function __construct(string $className, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Class "%s" not found', $className), $code, $previous);
    }
}
