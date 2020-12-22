<?php

declare(strict_types=1);

namespace AppBundle\Exception;

class InterfaceException extends \Exception
{
    public function __construct(string $className, string $interfaceName, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Class "%s" must implement %s', $className, $interfaceName),
            $code,
            $previous
        );
    }
}
