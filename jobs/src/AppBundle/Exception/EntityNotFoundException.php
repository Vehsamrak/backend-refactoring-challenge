<?php

declare(strict_types=1);

namespace AppBundle\Exception;

class EntityNotFoundException extends \InvalidArgumentException
{
    public function __construct($id, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Entity "%s" was not found', $id),
            $code,
            $previous
        );
    }
}
