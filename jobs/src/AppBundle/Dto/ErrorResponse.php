<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(array $errors, int $statusCode, $headers = [], $json = false)
    {
        parent::__construct(['errors' => $errors], $statusCode, $headers, $json);
    }
}
